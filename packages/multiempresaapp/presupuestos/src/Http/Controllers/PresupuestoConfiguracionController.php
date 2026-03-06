<?php

namespace MultiempresaApp\Presupuestos\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MultiempresaApp\Presupuestos\Models\PresupuestoConfiguracion;

class PresupuestoConfiguracionController extends Controller
{
    public function index()
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);

        return view('presupuestos::presupuestos.configuracion', compact('config'));
    }

    public function update(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);

        $validated = $request->validate([
            'iva_defecto'          => 'required|numeric|in:0,4,10,21',
            'prefijo'              => 'nullable|string|max:20',
            'validez_dias'         => 'nullable|integer|min:1',
            'forma_pago_defecto'   => 'nullable|string|max:255',
            'observaciones_defecto' => 'nullable|string',
        ]);

        $config->update($validated);

        return redirect()->route('admin.presupuestos.configuracion')
            ->with('success', 'Configuración guardada correctamente.');
    }
}
