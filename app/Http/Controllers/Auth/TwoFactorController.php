<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function setup(Request $request)
    {
        $user = $request->user();
        $google2fa = new Google2FA();

        if (! $user->two_factor_secret) {
            $secret = $google2fa->generateSecretKey();
            $user->update(['two_factor_secret' => $secret]);
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        return view('auth.two-factor.setup', compact('qrCodeUrl', 'user'));
    }

    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'El código ingresado no es válido.']);
        }

        $user->update([
            'two_factor_enabled'      => true,
            'two_factor_confirmed_at' => now(),
        ]);

        session(['two_factor_verified' => true]);

        return redirect()->route('profile.edit')
            ->with('success', 'Autenticación en dos pasos activada correctamente.');
    }

    public function disable(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'El código ingresado no es válido.']);
        }

        $user->update([
            'two_factor_secret'       => null,
            'two_factor_enabled'      => false,
            'two_factor_confirmed_at' => null,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Autenticación en dos pasos desactivada.');
    }

    public function challenge()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (session('two_factor_verified')) {
            return redirect()->intended(route('dashboard'));
        }

        return view('auth.two-factor.challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = $request->user();
        $google2fa = new Google2FA();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if (! $valid) {
            return back()->withErrors(['code' => 'El código de verificación no es válido.']);
        }

        session(['two_factor_verified' => true]);

        return redirect()->intended(route('dashboard'));
    }
}
