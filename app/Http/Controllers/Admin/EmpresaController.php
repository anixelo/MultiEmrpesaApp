<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;
        $company   = auth()->user()->company;
        $empresas  = Empresa::where('company_id', $companyId)->latest()->paginate(15);

        $plan        = $company?->activePlan();
        $maxEmpresas = $plan ? ($plan->max_empresas ?? 0) : 0;
        $currentCount = Empresa::where('company_id', $companyId)->where('active', true)->count();

        return view('admin.empresas.index', compact('empresas', 'maxEmpresas', 'currentCount'));
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
            'logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'active'  => 'boolean',
        ]);

        $validated['company_id'] = auth()->user()->company_id;
        $validated['active']     = $request->boolean('active', true);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('empresas/logos', 'public');
        } else {
            unset($validated['logo']);
        }

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
            'logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:2048',
            'active'  => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', false);

        if ($request->hasFile('logo')) {
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $validated['logo'] = $request->file('logo')->store('empresas/logos', 'public');
        } elseif ($request->boolean('remove_logo')) {
            if ($empresa->logo) {
                Storage::disk('public')->delete($empresa->logo);
            }
            $validated['logo'] = null;
        } else {
            unset($validated['logo']);
        }

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

        if ($empresa->logo) {
            Storage::disk('public')->delete($empresa->logo);
        }

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}
