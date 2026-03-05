<x-guest-layout>
    <div class="text-center mb-6">
        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mx-auto mb-3">
            <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>
        <h2 class="text-lg font-bold text-gray-900">Verificación en dos pasos</h2>
        <p class="text-sm text-gray-500 mt-1">Introduce el código de tu aplicación autenticadora</p>
    </div>

    <form method="POST" action="{{ route('two-factor.verify') }}" class="space-y-4">
        @csrf
        <div>
            <x-input-label for="code" :value="__('Código de verificación')" />
            <x-text-input id="code" name="code" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="6"
                          autocomplete="one-time-code" autofocus required
                          class="mt-1 block w-full text-center text-xl tracking-widest"
                          placeholder="000000" />
            <x-input-error :messages="$errors->get('code')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center">
            Verificar
        </x-primary-button>
    </form>
</x-guest-layout>
