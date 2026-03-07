<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $empresas  = Empresa::where('company_id', $companyId)->latest()->paginate(15);

        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        $company = auth()->user()->company;

        if ($company && !$company->canCreateEmpresa()) {
            return redirect()->route('admin.empresas.index')
                ->with('error', 'Has alcanzado el límite de empresas activas de tu plan.');
        }

        return view('admin.empresas.create');
    }

    public function store(Request $request)
    {
        $company = auth()->user()->company;

        if ($company && !$company->canCreateEmpresa()) {
            return redirect()->route('admin.empresas.index')
                ->with('error', 'Has alcanzado el límite de empresas activas de tu plan.');
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'nif'     => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'active'  => 'boolean',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['active']     = $request->boolean('active', true);

        Empresa::create($validated);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($empresa->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('admin.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($empresa->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'nif'     => 'nullable|string|max:50',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'active'  => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', false);

        $empresa->update($validated);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);

        if ($empresa->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $empresa->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}
