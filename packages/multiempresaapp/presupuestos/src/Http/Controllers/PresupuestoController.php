<?php

namespace MultiempresaApp\Presupuestos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\User;
use App\Notifications\PresupuestoEstadoNotification;
use App\Notifications\PresupuestoTrabajadorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use MultiempresaApp\Notas\Models\Nota;
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
        $empresaId  = auth()->user()->company_id;
        $config     = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);
        $notaId     = $request->query('nota_id');
        $plantillaId = $request->query('plantilla_id');

        return view('presupuestos::presupuestos.create', compact('config', 'notaId', 'plantillaId'));
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
            'lineas.*.concepto'           => 'nullable|string|max:255',
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

            if (empty(trim($lineaData['concepto'] ?? ''))) {
                continue;
            }

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

        // Link nota to this presupuesto if created from one
        if ($notaId = $request->input('nota_id')) {
            $nota = Nota::where('id', $notaId)
                ->where('empresa_id', $empresaId)
                ->first();

            if ($nota) {
                $nota->update(['presupuesto_id' => $presupuesto->id]);
                $this->logAudit($presupuesto, 'nota_aplicada', "Creado a partir de la nota: {$nota->titulo}");
            }
        }

        // Log plantilla usage if created from a template
        if ($plantillaId = $request->input('plantilla_id')) {
            $plantilla = \MultiempresaApp\PlantillasPresupuesto\Models\PlantillaPresupuesto::where('id', $plantillaId)
                ->where('empresa_id', $empresaId)
                ->first();

            if ($plantilla) {
                $this->logAudit($presupuesto, 'plantilla_aplicada', "Creado usando la plantilla: {$plantilla->nombre}");
            }
        }

        // If a worker creates a presupuesto, notify all company admins
        if (auth()->user()->hasRole('trabajador')) {
            $notification = new PresupuestoTrabajadorNotification($presupuesto->numero, $presupuesto->id, auth()->user(), 'creado');
            User::where('company_id', $empresaId)
                ->whereHas('roles', fn ($q) => $q->where('name', 'administrador'))
                ->each(fn (User $admin) => $admin->notify($notification));
        }

        return redirect()->route('admin.presupuestos.show', $presupuesto->id)
            ->with('success', 'Presupuesto creado correctamente.');
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas.servicio', 'audits.usuario'])->findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $company = auth()->user()->company;
        $canUseEnvioEnlace    = $company ? $company->canUseEnvioEnlace() : false;
        $canUseHistorialCambios = $company ? $company->canUseHistorialCambios() : false;

        return view('presupuestos::presupuestos.show', compact('presupuesto', 'canUseEnvioEnlace', 'canUseHistorialCambios'));
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

        $empresaId       = $presupuesto->empresa_id;
        $presupuestoNumero = $presupuesto->numero;
        $worker          = auth()->user()->hasRole('trabajador') ? auth()->user() : null;

        $presupuesto->delete();

        // If a worker deleted the presupuesto, notify all company admins
        if ($worker) {
            $notification = new PresupuestoTrabajadorNotification($presupuestoNumero, null, $worker, 'eliminado');
            User::where('company_id', $empresaId)
                ->whereHas('roles', fn ($q) => $q->where('name', 'administrador'))
                ->each(fn (User $admin) => $admin->notify($notification));
        }

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

        $this->notifyPresupuestoEstado($presupuesto);

        return redirect()->route('presupuestos.public', $token)
            ->with('success', 'Has aceptado el presupuesto.');
    }

    public function rechazar(string $token)
    {
        $presupuesto = Presupuesto::where('token_publico', $token)->firstOrFail();
        $presupuesto->update(['estado' => 'rechazado', 'rechazado_en' => now()]);
        $this->logAudit($presupuesto, 'rechazado', 'Presupuesto rechazado por el cliente.', null, null);

        $this->notifyPresupuestoEstado($presupuesto);

        return redirect()->route('presupuestos.public', $token)
            ->with('info', 'Has rechazado el presupuesto.');
    }

    private function notifyPresupuestoEstado(Presupuesto $presupuesto): void
    {
        $notification = new PresupuestoEstadoNotification($presupuesto);
        $notified     = collect();

        User::where('company_id', $presupuesto->empresa_id)
            ->whereHas('roles', fn ($q) => $q->where('name', 'administrador'))
            ->each(function (User $admin) use ($notification, &$notified) {
                $admin->notify($notification);
                $notified->push($admin->id);
            });

        if ($presupuesto->created_by && ! $notified->contains($presupuesto->created_by)) {
            $creator = User::find($presupuesto->created_by);
            $creator?->notify($notification);
        }
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
