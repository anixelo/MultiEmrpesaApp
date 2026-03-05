<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(),
                'password'          => bcrypt(\Illuminate\Support\Str::random(24)),
            ]
        );

        // Assign default role if none
        if ($user->roles->isEmpty()) {
            $user->assignRole('trabajador');
        }

        Auth::login($user);

        if ($user->two_factor_enabled) {
            session(['two_factor_verified' => false]);
            return redirect()->route('two-factor.challenge');
        }

        return redirect()->intended(route('dashboard'));
    }
}
