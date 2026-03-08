<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Configuración del Sistema</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gestiona los parámetros globales de la plataforma</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Promo zone --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-purple-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">Zona de Promoción para Nuevas Cuentas</h2>
                        <p class="text-xs text-gray-500">Configura el plan y duración de la promoción que se aplicará al registrarse</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.settings.update') }}" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Plan selector --}}
                <div>
                    <label for="promo_plan_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Plan de promoción
                    </label>
                    <select id="promo_plan_id" name="promo_plan_id"
                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sin promoción (usa el plan gratuito por defecto)</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ $promo_plan_id == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) . '/mes' }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-400 mt-1">El plan que se aplicará automáticamente a todas las nuevas cuentas.</p>
                    @error('promo_plan_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Months --}}
                <div>
                    <label for="promo_months" class="block text-sm font-medium text-gray-700 mb-1">
                        Meses de promoción
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="number" id="promo_months" name="promo_months" min="0" max="120"
                               value="{{ old('promo_months', $promo_months) }}"
                               class="w-28 rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <span class="text-sm text-gray-500">meses (0 = sin límite de tiempo)</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Duración de la promoción. Al finalizar, la cuenta deberá contratar un plan.</p>
                    @error('promo_months')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Preview --}}
                @if($promo_plan_id && $promo_months > 0)
                @php $promoPlan = $plans->firstWhere('id', $promo_plan_id); @endphp
                @if($promoPlan)
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
                    <strong>Configuración activa:</strong>
                    Las nuevas cuentas recibirán el plan <strong>{{ $promoPlan->name }}</strong>
                    de forma gratuita durante <strong>{{ $promo_months }} {{ $promo_months == 1 ? 'mes' : 'meses' }}</strong>.
                </div>
                @endif
                @elseif(!$promo_plan_id)
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600">
                    <strong>Sin promoción activa.</strong> Las nuevas cuentas recibirán el plan gratuito por defecto.
                </div>
                @endif

                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Guardar configuración
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
