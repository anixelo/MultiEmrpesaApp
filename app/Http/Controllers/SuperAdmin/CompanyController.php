<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::withCount('users');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $companies = $query->latest()->paginate(15)->withQueryString();

        return view('superadmin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('superadmin.companies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'active'  => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Ensure unique slug
        $slug = $validated['slug'];
        $count = 1;
        while (Company::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $slug . '-' . $count++;
        }

        Company::create($validated);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Company $company)
    {
        return view('superadmin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'active'  => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');

        $company->update($validated);

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa eliminada correctamente.');
    }
}
