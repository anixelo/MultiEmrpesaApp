<?php

namespace MultiempresaApp\Plans\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use MultiempresaApp\Plans\Models\Plan;
use MultiempresaApp\Plans\Models\Subscription;
use MultiempresaApp\Plans\Notifications\PlanChangedNotification;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->latest()->paginate(15);
        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price_monthly'    => 'required|numeric|min:0',
            'price_yearly'     => 'required|numeric|min:0',
            'max_users'        => 'required|integer|min:1',
            'max_presupuestos' => 'required|integer|min:0',
            'max_empresas'     => 'required|integer|min:0',
            'features'         => 'nullable|string',
            'has_tasks'        => 'boolean',
            'has_notes'        => 'boolean',
            'has_plantillas'   => 'boolean',
            'has_envio_enlace' => 'boolean',
            'has_historial_cambios' => 'boolean',
            'active'           => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);
        $validated['has_tasks'] = $request->boolean('has_tasks', false);
        $validated['has_notes'] = $request->boolean('has_notes', false);
        $validated['has_plantillas'] = $request->boolean('has_plantillas', false);
        $validated['has_envio_enlace'] = $request->boolean('has_envio_enlace', false);
        $validated['has_historial_cambios'] = $request->boolean('has_historial_cambios', false);
        $validated['features'] = $request->features
            ? array_filter(array_map('trim', explode("\n", $request->features)))
            : [];

        Plan::create($validated);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan creado correctamente.');
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'price_monthly'    => 'required|numeric|min:0',
            'price_yearly'     => 'required|numeric|min:0',
            'max_users'        => 'required|integer|min:1',
            'max_presupuestos' => 'required|integer|min:0',
            'max_empresas'     => 'required|integer|min:0',
            'features'         => 'nullable|string',
            'has_tasks'        => 'boolean',
            'has_notes'        => 'boolean',
            'has_plantillas'   => 'boolean',
            'has_envio_enlace' => 'boolean',
            'has_historial_cambios' => 'boolean',
            'active'           => 'boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        $validated['has_tasks'] = $request->boolean('has_tasks');
        $validated['has_notes'] = $request->boolean('has_notes');
        $validated['has_plantillas'] = $request->boolean('has_plantillas');
        $validated['has_envio_enlace'] = $request->boolean('has_envio_enlace');
        $validated['has_historial_cambios'] = $request->boolean('has_historial_cambios');
        $validated['features'] = $request->features
            ? array_filter(array_map('trim', explode("\n", $request->features)))
            : [];

        $plan->update($validated);

        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan actualizado correctamente.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'No se puede eliminar un plan con suscripciones activas.');
        }
        $plan->delete();
        return redirect()->route('superadmin.plans.index')
            ->with('success', 'Plan eliminado correctamente.');
    }

    public function assignPlan(Request $request, Company $company)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        Subscription::updateOrCreate(
            ['empresa_id' => $company->id],
            [
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'started_at' => now(),
                'expires_at' => $plan->isFree() ? null : now()->addMonth(),
            ]
        );

        // Notify company admin
        $admin = $company->users()->whereHas('roles', fn ($q) => $q->where('name', 'administrador'))->first();
        if ($admin) {
            $admin->notify(new PlanChangedNotification($plan));
        }

        return back()->with('success', 'Plan asignado correctamente.');
    }
}
