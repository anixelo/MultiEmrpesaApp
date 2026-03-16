<?php

namespace MultiempresaApp\PlantillasPresupuesto\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MultiempresaApp\PlantillasPresupuesto\Models\PlantillaPresupuesto;

class PlantillaPresupuestoController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $company   = auth()->user()->company;

        if ($company && ! $company->canUsePlantillas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de plantillas.');
        }

        $query = PlantillaPresupuesto::with(['negocio', 'lineas'])
            ->deEmpresa($empresaId);

        if ($buscar = $request->input('buscar')) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        $plantillas = $query->latest()->paginate(15)->withQueryString();

        return view('plantillas-presupuesto::plantillas-presupuesto.index', compact('plantillas'));
    }

    public function show($id)
    {
        $company = auth()->user()->company;

        if ($company && ! $company->canUsePlantillas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de plantillas.');
        }

        $plantilla = PlantillaPresupuesto::with(['negocio', 'lineas', 'creador'])
            ->findOrFail($id);

        if ($plantilla->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('plantillas-presupuesto::plantillas-presupuesto.show', compact('plantilla'));
    }

    public function destroy($id)
    {
        $company = auth()->user()->company;

        if ($company && ! $company->canUsePlantillas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de plantillas.');
        }

        $plantilla = PlantillaPresupuesto::findOrFail($id);

        if ($plantilla->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $plantilla->delete();

        return redirect()->route('admin.plantillas-presupuesto.index')
            ->with('success', 'Plantilla eliminada correctamente.');
    }

    public function usar($id)
    {
        $company = auth()->user()->company;

        if ($company && ! $company->canUsePlantillas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de plantillas.');
        }

        $plantilla = PlantillaPresupuesto::findOrFail($id);

        if ($plantilla->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return redirect()->route('admin.presupuestos.create', ['plantilla_id' => $plantilla->id]);
    }
}
