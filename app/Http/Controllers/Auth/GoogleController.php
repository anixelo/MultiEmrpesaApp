<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Company;
use MultiempresaApp\Plans\Models\Plan;
use MultiempresaApp\Plans\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'No se pudo autenticar con Google. Por favor, intenta de nuevo.');
        }

        // Buscar usuario por email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Login de usuario existente: actualiza datos y loguea
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'name' => $googleUser->getName() ?? $user->name,
                'email_verified_at' => now(),
            ]);
        } else {
            // Registro nuevo: crear empresa, asignar plan, suscripción y usuario administrador

            // Crear slug único para la empresa (con nombre de usuario Google)
            $companyName = $googleUser->getName() ?: 'Empresa';
            $slug = Str::slug($companyName);
            $base = $slug;
            $count = 1;
            while (Company::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $count++;
            }

            // Asignar plan promo o gratuito
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
                'name'   => $companyName,
                'slug'   => $slug,
                'email'  => $googleUser->getEmail(),
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

            // Crear el usuario administrador vinculado a la nueva empresa
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'google_id'         => $googleUser->getId(),
                'avatar'            => $googleUser->getAvatar(),
                'company_id'        => $company->id,
                'email_verified_at' => now(),
                'password'          => Hash::make(Str::random(24)),
            ]);

            $user->assignRole('administrador');

            event(new Registered($user));
        }

        Auth::login($user);

        if (isset($user->two_factor_enabled) && $user->two_factor_enabled) {
            session(['two_factor_verified' => false]);
            return redirect()->route('two-factor.challenge');
        }

        return redirect()->intended(route('dashboard'));
    }
}