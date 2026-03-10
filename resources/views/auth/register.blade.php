<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Honeypot: leave empty --}}
        <div class="hidden" aria-hidden="true" style="display:none!important">
            <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
        </div>

        <!-- Account Name -->
        <div>
            <x-input-label for="company_name" :value="__('Nombre de tu cuenta')" />
            <x-text-input id="company_name" class="block mt-1 w-full" type="text" name="company_name" :value="old('company_name')" required autofocus autocomplete="organization" />
            <p class="mt-1 text-xs text-gray-500">{{ __('Es el nombre que se mostrará al acceder a la aplicación (puede ser el de tu empresa u organización).') }}</p>
            <x-input-error :messages="$errors->get('company_name')" class="mt-2" />
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Tu nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Privacy notice --}}
        <div class="mt-5 rounded-xl bg-gray-50 border border-gray-200 p-4 text-xs text-gray-600 leading-relaxed">
            <svg class="w-4 h-4 inline-block text-indigo-500 mr-1 align-text-bottom" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            {{ config('app.name') }} únicamente utiliza los datos personales en la medida necesaria para prestar nuestros Servicios.
            Para más detalles puedes consultar nuestra
            <a href="{{ route('pages.privacy') }}" class="text-indigo-600 hover:underline font-medium" target="_blank">Política de Privacidad</a>.
            Al registrarme acepto los
            <a href="{{ route('pages.terms') }}" class="text-indigo-600 hover:underline font-medium" target="_blank">términos y condiciones</a>.
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya tienes cuenta?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Crear cuenta') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
