<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">Autenticación en Dos Pasos</h1>
        <p class="text-sm text-gray-500 mt-0.5">Protege tu cuenta con verificación adicional</p>
    </x-slot>

    <div class="max-w-lg mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if($user->two_factor_enabled)
        <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">2FA está activo</p>
                    <p class="text-xs text-gray-500">Tu cuenta está protegida</p>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-4">Para desactivar la autenticación en dos pasos, introduce el código actual de tu aplicación autenticadora.</p>

            <form method="POST" action="{{ route('two-factor.disable') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código de verificación</label>
                    <input type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code"
                           class="w-full border-gray-300 rounded-xl text-sm text-center text-lg tracking-widest focus:ring-red-500 focus:border-red-500 @error('code') border-red-300 @enderror"
                           placeholder="000000">
                    @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-xl text-sm font-medium hover:bg-red-700 transition">
                    Desactivar 2FA
                </button>
            </form>
        </div>

        @else
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <div>
                <h2 class="font-semibold text-gray-900 mb-2">1. Escanea el código QR</h2>
                <p class="text-sm text-gray-600 mb-4">Abre tu aplicación autenticadora (Google Authenticator, Authy, etc.) y escanea este código:</p>
                <div class="flex justify-center p-4 bg-gray-50 rounded-xl border border-gray-200">
                    {!! renderQrCode($qrCodeUrl) !!}
                </div>
            </div>

            <div>
                <h2 class="font-semibold text-gray-900 mb-2">2. Introduce el código</h2>
                <p class="text-sm text-gray-600 mb-3">Una vez escaneado, introduce el código de 6 dígitos que muestra la app:</p>

                <form method="POST" action="{{ route('two-factor.enable') }}" class="space-y-4">
                    @csrf
                    <div>
                        <input type="text" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code"
                               class="w-full border-gray-300 rounded-xl text-sm text-center text-lg tracking-widest focus:ring-indigo-500 focus:border-indigo-500 @error('code') border-red-300 @enderror"
                               placeholder="000000">
                        @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                        Activar 2FA
                    </button>
                </form>
            </div>
        </div>
        @endif

        <a href="{{ route('profile.edit') }}" class="block text-center text-sm text-gray-400 hover:text-gray-600 transition">
            ← Volver al perfil
        </a>
    </div>
</x-app-layout>
