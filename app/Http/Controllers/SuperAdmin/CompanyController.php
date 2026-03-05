<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use MultiempresaApp\Plans\Models\Plan;
use MultiempresaApp\Plans\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::withCount('users')->with('subscription.plan');

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

        $company = Company::create($validated);

        // Auto-assign free plan to new company
        $freePlan = Plan::where('price_monthly', 0)->where('active', true)->first();
        if ($freePlan) {
            Subscription::create([
                'empresa_id' => $company->id,
                'plan_id'    => $freePlan->id,
                'status'     => 'active',
                'started_at' => now(),
            ]);
        }

        return redirect()->route('superadmin.companies.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Company $company)
    {
        $plans = Plan::where('active', true)->orderBy('price_monthly')->get();
        $currentPlanId = $company->subscription?->plan_id;

        return view('superadmin.companies.edit', compact('company', 'plans', 'currentPlanId'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'active'  => 'boolean',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        $validated['active'] = $request->boolean('active');

        $company->update(Arr::except($validated, ['plan_id']));

        // Update plan if provided
        if ($request->filled('plan_id')) {
            $subscription = $company->subscription;
            if ($subscription) {
                $subscription->update(['plan_id' => $validated['plan_id']]);
            } else {
                Subscription::create([
                    'empresa_id' => $company->id,
                    'plan_id'    => $validated['plan_id'],
                    'status'     => 'active',
                    'started_at' => now(),
                ]);
            }
        }

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
