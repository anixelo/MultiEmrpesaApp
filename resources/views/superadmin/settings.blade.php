<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Configuración del sistema</h1>
            <p class="mt-1 text-sm text-slate-500">Gestiona los parámetros globales de la plataforma</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                <svg class="h-5 w-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-violet-50 px-6 py-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 shadow-inner">
                        <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                        </svg>
                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Zona de promoción para nuevas cuentas</h2>
                        <p class="mt-1 text-xs text-slate-500">Configura el plan y duración de la promoción que se aplicará al registrarse</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('superadmin.settings.update') }}" class="space-y-6 p-6 sm:p-8">
                @csrf
                @method('PUT')

                <div>
                    <label for="promo_plan_id" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Plan de promoción
                    </label>
                    <select
                        id="promo_plan_id"
                        name="promo_plan_id"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >
                        <option value="">Sin promoción (usa el plan gratuito por defecto)</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" {{ $promo_plan_id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} — {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) . '/mes' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1.5 text-xs text-slate-400">El plan que se aplicará automáticamente a todas las nuevas cuentas.</p>
                    @error('promo_plan_id')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="promo_months" class="mb-1.5 block text-sm font-medium text-slate-700">
                        Meses de promoción
                    </label>

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-3">
                        <input
                            type="number"
                            id="promo_months"
                            name="promo_months"
                            min="0"
                            max="120"
                            value="{{ old('promo_months', $promo_months) }}"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 sm:w-32"
                        >
                        <span class="text-sm text-slate-500">meses (0 = sin límite de tiempo)</span>
                    </div>

                    <p class="mt-1.5 text-xs text-slate-400">Duración de la promoción. Al finalizar, la cuenta deberá contratar un plan.</p>
                    @error('promo_months')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                @if($promo_plan_id && $promo_months > 0)
                    @php $promoPlan = $plans->firstWhere('id', $promo_plan_id); @endphp
                    @if($promoPlan)
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 shadow-sm">
                            <strong>Configuración activa:</strong>
                            Las nuevas cuentas recibirán el plan <strong>{{ $promoPlan->name }}</strong>
                            de forma gratuita durante <strong>{{ $promo_months }} {{ $promo_months == 1 ? 'mes' : 'meses' }}</strong>.
                        </div>
                    @endif
                @elseif(!$promo_plan_id)
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600 shadow-sm">
                        <strong>Sin promoción activa.</strong> Las nuevas cuentas recibirán el plan gratuito por defecto.
                    </div>
                @endif

                <div class="flex justify-end border-t border-slate-100 pt-5">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700"
                    >
                        Guardar configuración
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-app-layout>