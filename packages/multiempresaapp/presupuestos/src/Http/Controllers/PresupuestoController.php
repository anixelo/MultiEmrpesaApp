<?php

namespace MultiempresaApp\Presupuestos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
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

    public function index(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $query = Presupuesto::with(['cliente'])
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

        return view('presupuestos::presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);

        return view('presupuestos::presupuestos.create', compact('config'));
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);

        $validated = $request->validate([
            'cliente_id'                  => 'required|exists:clientes,id',
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

        return redirect()->route('admin.presupuestos.index')
            ->with('success', 'Presupuesto creado correctamente.');
    }

    public function show($id)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas.servicio'])->findOrFail($id);

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

        $config = PresupuestoConfiguracion::getOrCreateForEmpresa(auth()->user()->company_id);

        return view('presupuestos::presupuestos.edit', compact('presupuesto', 'config'));
    }

    public function update(Request $request, $id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $config = PresupuestoConfiguracion::getOrCreateForEmpresa(auth()->user()->company_id);

        $validated = $request->validate([
            'cliente_id'                  => 'required|exists:clientes,id',
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

        $presupuesto->update([
            'cliente_id'   => $validated['cliente_id'],
            'fecha'        => $validated['fecha'],
            'validez_hasta' => $validated['validez_hasta'] ?? null,
            'forma_pago'   => $validated['forma_pago'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
            'notas'        => $validated['notas'] ?? null,
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

        return redirect()->route('admin.presupuestos.index')
            ->with('success', 'Presupuesto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $presupuesto = Presupuesto::findOrFail($id);

        if ($presupuesto->empresa_id !== auth()->user()->company_id) {
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

        return redirect()->back()->with('success', 'Presupuesto marcado como enviado.');
    }

    public function duplicar($id)
    {
        $original = Presupuesto::with('lineas')->findOrFail($id);

        if ($original->empresa_id !== auth()->user()->company_id) {
            abort(403);
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

        return redirect()->route('admin.presupuestos.edit', $nuevo->id)
            ->with('success', 'Presupuesto duplicado correctamente.');
    }

    public function public(string $token)
    {
        $presupuesto = Presupuesto::with(['cliente', 'lineas', 'empresa'])
            ->where('token_publico', $token)
            ->firstOrFail();

        if (! $presupuesto->visto_en) {
            $presupuesto->update([
                'visto_en' => now(),
                'estado'   => $presupuesto->estado === 'enviado' ? 'visto' : $presupuesto->estado,
            ]);
        }

        return view('presupuestos::presupuestos.public', compact('presupuesto'));
    }

    public function aceptar(string $token)
    {
        $presupuesto = Presupuesto::where('token_publico', $token)->firstOrFail();
        $presupuesto->update(['estado' => 'aceptado', 'aceptado_en' => now()]);

        return redirect()->route('presupuestos.public', $token)
            ->with('success', 'Has aceptado el presupuesto.');
    }

    public function rechazar(string $token)
    {
        $presupuesto = Presupuesto::where('token_publico', $token)->firstOrFail();
        $presupuesto->update(['estado' => 'rechazado', 'rechazado_en' => now()]);

        return redirect()->route('presupuestos.public', $token)
            ->with('info', 'Has rechazado el presupuesto.');
    }
}
