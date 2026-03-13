<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Company;
use App\Notifications\NuevaCuentaNotification;
use MultiempresaApp\Plans\Models\Plan;
use MultiempresaApp\Plans\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Honeypot: if the hidden field is filled, it's a bot
        if ($request->filled('website')) {
            return redirect()->route('register');
        }

        $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create the company with a unique slug
        $slug = Str::slug($request->company_name);
        $base = $slug;
        $count = 1;
        while (Company::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        // Assign plan to the new company (promo or free)
        $promoPlanId  = AppSetting::get('promo_plan_id');
        $promoMonths  = (int) AppSetting::get('promo_months', 0);
        $planToAssign = null;
        $promoEndsAt  = null;

        if ($promoPlanId && $promoMonths > 0) {
            $planToAssign = Plan::where('id', $promoPlanId)->where('active', true)->first();
        }

        if (!$planToAssign) {
            $planToAssign = Plan::where('price_monthly', 0)->where('active', true)->first();
        } else {
            $promoEndsAt = now()->addMonths($promoMonths);
        }

        $companyData = [
            'name'   => $request->company_name,
            'slug'   => $slug,
            'active' => true,
        ];

        if ($promoEndsAt) {
            $companyData['promo_plan_id'] = $planToAssign->id;
            $companyData['promo_ends_at'] = $promoEndsAt;
        }

        $company = Company::create($companyData);

        if ($planToAssign) {
            Subscription::create([
                'empresa_id' => $company->id,
                'plan_id'    => $planToAssign->id,
                'status'     => 'active',
                'started_at' => now(),
            ]);
        }

        // Create the admin user linked to this company
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'company_id'        => $company->id,
            'email_verified_at' => now(),
        ]);

        $user->assignRole('administrador');

        event(new Registered($user));

        // Notify all superadmins about the new account
        $notification = new NuevaCuentaNotification($company);
        User::whereHas('roles', fn ($q) => $q->where('name', 'superadministrador'))
            ->each(fn (User $superAdmin) => $superAdmin->notify($notification));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
