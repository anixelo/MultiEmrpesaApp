<?php

namespace MultiempresaApp\Plans\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use MultiempresaApp\Plans\Models\Plan;
use MultiempresaApp\Plans\Models\Subscription;
use MultiempresaApp\Plans\Notifications\PlanChangedNotification;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asignada.');
        }

        $subscription = $company->subscription()->with('plan')->first();
        $plans = Plan::active()->get();

        return view('admin.subscription', compact('company', 'subscription', 'plans'));
    }

    public function changePlan(Request $request)
    {
        $request->validate(['plan_id' => 'required|exists:plans,id']);

        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return back()->with('error', 'No tienes una empresa asignada.');
        }

        if ($company->isInPromo()) {
            return back()->with('error', 'No puedes cambiar de plan mientras tienes una promoción activa.');
        }

        $plan = Plan::findOrFail($request->plan_id);

        Subscription::updateOrCreate(
            ['empresa_id' => $company->id],
            [
                'plan_id'    => $plan->id,
                'status'     => 'active',
                'started_at' => now(),
                'expires_at' => null,
            ]
        );

        $user->notify(new PlanChangedNotification($plan));

        return back()->with('success', 'Plan actualizado correctamente.');
    }
}
