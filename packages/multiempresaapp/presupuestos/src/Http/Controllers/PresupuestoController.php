<?php

namespace MultiempresaApp\Presupuestos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
use MultiempresaApp\Presupuestos\Models\PresupuestoAudit;
use MultiempresaApp\Presupuestos\Models\PresupuestoConfiguracion;
use MultiempresaApp\Presupuestos\Models\PresupuestoLinea;
use MultiempresaApp\Presupuestos\Services\PresupuestoCalculator;

class PresupuestoController extends Controller
{
    protected PresupuestoCalculator $calculator;

    public function __construct(PresupuestoCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    protected function logAudit(Presupuesto $presupuesto, string $accion, ?string $descripcion = null, ?array $datos = null, ?int $userId = null): void
    {
        PresupuestoAudit::create([
            'presupuesto_id' => $presupuesto->id,
            'user_id'        => $userId ?? auth()->id(),
            'accion'         => $accion,
            'descripcion'    => $descripcion,
            'datos'          => $datos,
        ]);
    }

    protected function normalizeFieldValue(mixed $value): string
    {
        if ($value instanceof \Illuminate\Support\Carbon) {
            return $value->toDateString();
        }
        return (string) ($value ?? '');
    }

    public function index(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $query = Presupuesto::with(['cliente', 'negocio'])
            ->where('empresa_id', $empresaId);

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('numero', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', fn ($q2) => $q2->where('nombre', 'like', "%{$buscar}%"));
            });
        }

        if ($estado = $request->input('estado')) {
            $query->where('estado', $estado);
        }

        $presupuestos = $query->latest()->paginate(15)->withQueryString();

        $hasEmpresa = Empresa::where('company_id', $empresaId)->exists();

        $company = auth()->user()->company;
        $plan = $company?->activePlan();
        $maxPresupuestos = $plan ? ($plan->max_presupuestos ?? 0) : 0;
        $currentMonthCount = $company ? $company->presupuestos()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count() : 0;

        return view('presupuestos::presupuestos.index', compact('presupuestos', 'hasEmpresa', 'maxPresupuestos', 'currentMonthCount'));
    }

    public function create(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);
        $notaId    = $request->query('nota_id');

        return view('presupuestos::presupuestos.create', compact('config', 'notaId'));
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);

        $company = auth()->user()->company;
        if ($company && !$company->canCreatePresupuesto()) {
            return redirect()->back()->with('error', 'Has alcanzado el límite de presupuestos de tu plan para este mes.');
        }

        $validated = $request->validate([
            'cliente_id'                  => 'required|exists:clientes,id',
            'negocio_id'                  => 'nullable|exists:empresas,id',
            'fecha'                       => 'required|date',
            'validez_hasta'               => 'nullable|date',
            'forma_pago'                  => 'nullable|string|max:255',
            'observaciones'               => 'nullable|string',
            'notas'                       => 'nullable|string',
            'lineas'                      => 'required|array|min:1',
            'lineas.*.concepto'           => 'required|string|max:255',
            'lineas.*.cantidad'           => 'required|numeric|min:0',
            'lineas.*.precio_unitario'    => 'required|numeric|min:0',
            'lineas.*.iva_tipo'           => 'nullable|numeric',
            'lineas.*.descuento_tipo'     => 'nullable|in:porcentaje,importe',
            'lineas.*.descuento_valor'    => 'nullable|numeric|min:0',
        ]);

        $numero = $this->calculator->generarNumero($config);

        $presupuesto = Presupuesto::create([
            'empresa_id'   => $empresaId,
            'negocio_id'   => $validated['negocio_id'] ?? null,
            'cliente_id'   => $validated['cliente_id'],
            'numero'       => $numero,
            'fecha'        => $validated['fecha'],
            'estado'       => 'borrador',
            'validez_hasta' => $validated['validez_hasta'] ?? null,
            'forma_pago'   => $validated['forma_pago'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
            'notas'        => $validated['notas'] ?? null,
            'created_by'   => auth()->id(),
        ]);

        foreach ($validated['lineas'] as $i => $lineaData) {
            $calculated = $this->calculator->calcularLinea($lineaData, (float) $config->iva_defecto);
            PresupuestoLinea::create([
                'presupuesto_id'  => $presupuesto->id,
                'servicio_id'     => $lineaData['servicio_id'] ?? null,
                'orden'           => $i,
                'concepto'        => $lineaData['concepto'],
                'cantidad'        => $calculated['cantidad'],
                'precio_unitario' => $calculated['precio_unitario'],
                'descuento_tipo'  => $calculated['descuento_tipo'] ?? null,
                'descuento_valor' => $calculated['descuento_valor'] ?? null,
                'base_imponible'  => $calculated['base_imponible'],
                'iva_tipo'        => $calculated['iva_tipo'],
                'iva_cuota'       => $calculated['iva_cuota'],
                'total'           => $calculated['total'],
            ]);
        }

        $this->calculator->calcularTotales($presupuesto);

        $this->logAudit($presupuesto, 'creado', 'Presupuesto creado con estado Borrador.');

        return redirect()->route('admin.presupuestos.show', $presupuesto->id)
            ->with('success', 'Presupuesto creado correctamente.');
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas.servicio', 'audits.usuario'])->findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('presupuestos::presupuestos.show', compact('presupuesto'));
    }

    public function edit($id)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas'])->findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $presupuesto->created_by !== auth()->id()) {
            abort(403);
        }

        $config = PresupuestoConfiguracion::getOrCreateForEmpresa(auth()->user()->company_id);

        return view('presupuestos::presupuestos.edit', compact('presupuesto', 'config'));
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $presupuesto->created_by !== auth()->id()) {
            abort(403);
        }

        $config = PresupuestoConfiguracion::getOrCreateForEmpresa(auth()->user()->company_id);

        $validated = $request->validate([
            'cliente_id'                  => 'required|exists:clientes,id',
            'negocio_id'                  => 'nullable|exists:empresas,id',
            'fecha'                       => 'required|date',
            'validez_hasta'               => 'nullable|date',
            'forma_pago'                  => 'nullable|string|max:255',
            'observaciones'               => 'nullable|string',
            'notas'                       => 'nullable|string',
            'lineas'                      => 'required|array|min:1',
            'lineas.*.concepto'           => 'required|string|max:255',
            'lineas.*.cantidad'           => 'required|numeric|min:0',
            'lineas.*.precio_unitario'    => 'required|numeric|min:0',
            'lineas.*.iva_tipo'           => 'nullable|numeric',
            'lineas.*.descuento_tipo'     => 'nullable|in:porcentaje,importe',
            'lineas.*.descuento_valor'    => 'nullable|numeric|min:0',
        ]);

        $cambios = [];
        $camposAudit = ['cliente_id', 'negocio_id', 'fecha', 'validez_hasta', 'forma_pago', 'observaciones', 'notas'];
        foreach ($camposAudit as $campo) {
            $anterior = $this->normalizeFieldValue($presupuesto->$campo);
            $nuevo    = $this->normalizeFieldValue($validated[$campo] ?? null);
            if ($anterior !== $nuevo) {
                $cambios[$campo] = ['antes' => $anterior ?: null, 'despues' => $nuevo ?: null];
            }
        }

        $totalAntes = (float) $presupuesto->total;

        $presupuesto->update([
            'cliente_id'    => $validated['cliente_id'],
            'negocio_id'    => $validated['negocio_id'] ?? null,
            'fecha'         => $validated['fecha'],
            'validez_hasta' => $validated['validez_hasta'] ?? null,
            'forma_pago'    => $validated['forma_pago'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
            'notas'         => $validated['notas'] ?? null,
        ]);

        $presupuesto->lineas()->delete();

        foreach ($validated['lineas'] as $i => $lineaData) {
            $calculated = $this->calculator->calcularLinea($lineaData, (float) $config->iva_defecto);
            PresupuestoLinea::create([
                'presupuesto_id'  => $presupuesto->id,
                'servicio_id'     => $lineaData['servicio_id'] ?? null,
                'orden'           => $i,
                'concepto'        => $lineaData['concepto'],
                'cantidad'        => $calculated['cantidad'],
                'precio_unitario' => $calculated['precio_unitario'],
                'descuento_tipo'  => $calculated['descuento_tipo'] ?? null,
                'descuento_valor' => $calculated['descuento_valor'] ?? null,
                'base_imponible'  => $calculated['base_imponible'],
                'iva_tipo'        => $calculated['iva_tipo'],
                'iva_cuota'       => $calculated['iva_cuota'],
                'total'           => $calculated['total'],
            ]);
        }

        $this->calculator->calcularTotales($presupuesto);

        $presupuesto->refresh();
        if (round((float) $presupuesto->total, 2) !== round($totalAntes, 2)) {
            $cambios['total'] = ['antes' => number_format($totalAntes, 2, '.', ''), 'despues' => number_format((float) $presupuesto->total, 2, '.', '')];
        }

        $this->logAudit($presupuesto, 'editado', 'Presupuesto editado.', $cambios ?: null);

        return redirect()->route('admin.presupuestos.index')
            ->with('success', 'Presupuesto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $presupuesto->created_by !== auth()->id()) {
            abort(403);
        }

        $presupuesto->delete();

        return redirect()->route('admin.presupuestos.index')
            ->with('success', 'Presupuesto eliminado correctamente.');
    }

    public function enviar($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $presupuesto->update(['estado' => 'enviado', 'enviado_en' => now()]);

        $this->logAudit($presupuesto, 'enviado', 'Presupuesto marcado como enviado.');

        return redirect()->back()->with('success', 'Presupuesto marcado como enviado.');
    }

    public function duplicar($id)
    {
        $original = Presupuesto::with('lineas')->findOrFail($id);

        if ($original->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $company = auth()->user()->company;
        if ($company && !$company->canCreatePresupuesto()) {
            return redirect()->back()->with('error', 'Has alcanzado el límite de presupuestos de tu plan para este mes.');
        }

        $config = PresupuestoConfiguracion::getOrCreateForEmpresa(auth()->user()->company_id);
        $numero = $this->calculator->generarNumero($config);

        $nuevo               = $original->replicate();
        $nuevo->numero       = $numero;
        $nuevo->estado       = 'borrador';
        $nuevo->token_publico = Str::random(32);
        $nuevo->enviado_en   = null;
        $nuevo->visto_en     = null;
        $nuevo->aceptado_en  = null;
        $nuevo->rechazado_en = null;
        $nuevo->created_by   = auth()->id();
        $nuevo->save();

        foreach ($original->lineas as $linea) {
            $nuevaLinea                 = $linea->replicate();
            $nuevaLinea->presupuesto_id = $nuevo->id;
            $nuevaLinea->save();
        }

        $this->logAudit($original, 'duplicado', "Presupuesto duplicado como {$nuevo->numero}.");
        $this->logAudit($nuevo, 'creado', "Creado como duplicado del presupuesto {$original->numero}.");

        return redirect()->route('admin.presupuestos.edit', $nuevo->id)
            ->with('success', 'Presupuesto duplicado correctamente.');
    }

    public function public(string $token)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas', 'empresa', 'negocio'])
            ->where('token_publico', $token)
            ->firstOrFail();

        if (! $presupuesto->visto_en) {
            $presupuesto->update([
                'visto_en' => now(),
                'estado'   => $presupuesto->estado === 'enviado' ? 'visto' : $presupuesto->estado,
            ]);
            $this->logAudit($presupuesto, 'visto', 'Presupuesto visto por el cliente.', null, null);
        }

        return view('presupuestos::presupuestos.public', compact('presupuesto'));
    }

    public function downloadPublicPdf(string $token)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas.servicio', 'empresa', 'negocio'])
            ->where('token_publico', $token)
            ->firstOrFail();

        $pdf = Pdf::loadView('presupuestos::presupuestos.pdf', compact('presupuesto'));

        return $pdf->download('presupuesto-' . $presupuesto->numero . '.pdf');
    }

    public function aceptar(string $token)
    {
        $presupuesto = Presupuesto::where('token_publico', $token)->firstOrFail();
        $presupuesto->update(['estado' => 'aceptado', 'aceptado_en' => now()]);
        $this->logAudit($presupuesto, 'aceptado', 'Presupuesto aceptado por el cliente.', null, null);

        return redirect()->route('presupuestos.public', $token)
            ->with('success', 'Has aceptado el presupuesto.');
    }

    public function rechazar(string $token)
    {
        $presupuesto = Presupuesto::where('token_publico', $token)->firstOrFail();
        $presupuesto->update(['estado' => 'rechazado', 'rechazado_en' => now()]);
        $this->logAudit($presupuesto, 'rechazado', 'Presupuesto rechazado por el cliente.', null, null);

        return redirect()->route('presupuestos.public', $token)
            ->with('info', 'Has rechazado el presupuesto.');
    }

    public function downloadPdf($id)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas.servicio', 'empresa', 'negocio'])->findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $pdf = Pdf::loadView('presupuestos::presupuestos.pdf', compact('presupuesto'));

        return $pdf->download('presupuesto-' . $presupuesto->numero . '.pdf');
    }

    public function sendEmail(Request $request, $id)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas', 'empresa', 'negocio'])->findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (!$presupuesto->cliente?->email) {
            return redirect()->back()->with('error', 'El cliente no tiene email registrado.');
        }

        $publicUrl    = route('presupuestos.public', $presupuesto->token_publico);
        $clienteNombre = $presupuesto->cliente->nombre;
        $empresaNombre = $presupuesto->negocio?->name ?? $presupuesto->empresa?->name ?? config('app.name');

        Mail::raw(
            "Hola {$clienteNombre},\n\nTe enviamos el presupuesto {$presupuesto->numero}.\n\nPuedes verlo aquí:\n{$publicUrl}\n\nGracias,\n{$empresaNombre}",
            function ($message) use ($presupuesto, $empresaNombre) {
                $message->to($presupuesto->cliente->email)
                        ->subject("Presupuesto {$presupuesto->numero} - {$empresaNombre}");
            }
        );

        $this->logAudit($presupuesto, 'email_enviado', "Email enviado a {$presupuesto->cliente->email}.");

        return redirect()->back()->with('success', 'Email enviado a ' . $presupuesto->cliente->email);
    }
}
