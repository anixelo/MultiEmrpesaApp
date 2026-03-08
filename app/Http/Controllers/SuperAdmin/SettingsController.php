<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use MultiempresaApp\Plans\Models\Plan;

class SettingsController extends Controller
{
    public function index(): View
    {
        $plans = Plan::active()->orderBy('price_monthly')->get();
        $promo_plan_id = AppSetting::get('promo_plan_id');
        $promo_months  = AppSetting::get('promo_months', 0);

        return view('superadmin.settings', compact('plans', 'promo_plan_id', 'promo_months'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'promo_plan_id' => ['nullable', 'exists:plans,id'],
            'promo_months'  => ['required', 'integer', 'min:0', 'max:120'],
        ]);

        AppSetting::set('promo_plan_id', $request->promo_plan_id ?: null);
        AppSetting::set('promo_months', (int) $request->promo_months);

        return back()->with('success', 'Configuración guardada correctamente.');
    }
}
