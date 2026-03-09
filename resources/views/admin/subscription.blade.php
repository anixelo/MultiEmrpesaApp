<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Mi Suscripción</h1>
            <p class="text-sm text-gray-500 mt-0.5">Gestiona el plan de tu empresa</p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm">{{ session('error') }}</div>
        @endif

        {{-- Promo info --}}
        @php $company = auth()->user()->company; @endphp
        @if($company && $company->isInPromo() && $company->promoPlan())
        @php $promoPlan = $company->promoPlan(); @endphp
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div class="relative flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg">🎉 Promoción activa</h3>
                    <p class="text-indigo-100 mt-1">
                        Estás disfrutando del plan <strong class="text-white">{{ $promoPlan->name }}</strong> de forma gratuita
                        hasta el <strong class="text-yellow-300">{{ $company->promo_ends_at->format('d \d\e F \d\e Y') }}</strong>.
                    </p>
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <div class="bg-white/10 rounded-lg p-2.5 text-center">
                            <div class="text-xl font-bold">{{ $promoPlan->max_users }}</div>
                            <div class="text-xs text-indigo-200">Usuarios</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-2.5 text-center">
                            <div class="text-xl font-bold">{{ ($promoPlan->max_presupuestos ?? 0) == 0 ? '∞' : $promoPlan->max_presupuestos }}</div>
                            <div class="text-xs text-indigo-200">Presup./mes</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-2.5 text-center">
                            <div class="text-xl font-bold text-yellow-300">Gratis</div>
                            <div class="text-xs text-indigo-200">Precio actual</div>
                        </div>
                        <div class="bg-white/10 rounded-lg p-2.5 text-center">
                            <div class="text-sm font-bold">{{ $company->promo_ends_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-indigo-200">Fin promoción</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Current plan --}}
        @if(!$company || !$company->isInPromo())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Plan Actual</h2>

            @if($subscription && $subscription->plan)
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <h3 class="text-lg font-bold text-gray-900">{{ $subscription->plan->name }}</h3>
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $subscription->isActive() ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $subscription->isActive() ? 'Activo' : ucfirst($subscription->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $subscription->plan->description }}</p>
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-gray-900">{{ $subscription->plan->max_users }}</div>
                            <div class="text-xs text-gray-500">Usuarios</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-gray-900">{{ $subscription->plan->max_presupuestos == 0 ? '∞' : $subscription->plan->max_presupuestos }}</div>
                            <div class="text-xs text-gray-500">Presup./mes</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xl font-bold text-gray-900">
                                {{ $subscription->plan->isFree() ? 'Gratis' : '€' . number_format($subscription->plan->price_monthly, 2) }}
                            </div>
                            <div class="text-xs text-gray-500">Por mes</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-sm font-semibold text-gray-900">
                                {{ $subscription->started_at?->format('d/m/Y') ?? '—' }}
                            </div>
                            <div class="text-xs text-gray-500">Inicio</div>
                        </div>
                    </div>
                    @if($subscription->plan->features)
                    <div class="mt-3">
                        <ul class="flex flex-wrap gap-2">
                            @foreach($subscription->plan->features as $feature)
                            <li class="inline-flex items-center gap-1 text-xs text-gray-600">
                                <svg class="w-3.5 h-3.5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @else
            <div class="text-center py-6 text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/></svg>
                <p class="text-sm">No tienes ningún plan activo.</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Available free plans --}}
        @php $freePlans = $plans->where('price_monthly', 0); @endphp
        @if($freePlans->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Planes Disponibles</h2>
            <p class="text-sm text-gray-500 mb-4">Puedes cambiar al plan gratuito directamente. Para planes de pago, contacta con el administrador.</p>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($plans as $plan)
                @php $isCurrentPlan = !$company->isInPromo() && ($subscription?->plan_id === $plan->id); @endphp
                <div class="border border-gray-200 rounded-xl p-4 {{ $isCurrentPlan ? 'border-indigo-300 bg-indigo-50' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                        @if($isCurrentPlan)
                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">Actual</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-1">
                        {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) }}
                        @if(!$plan->isFree())<span class="text-sm font-normal text-gray-400">/mes</span>@endif
                    </p>
                    <p class="text-xs text-gray-500 mb-3">{{ $plan->max_users }} usuarios · {{ ($plan->max_presupuestos ?? 0) == 0 ? '∞' : $plan->max_presupuestos }} presup./mes</p>
                    @if($company->isInPromo() && $subscription?->plan_id !== $plan->id)
                    <p class="text-xs text-center text-amber-600 italic">No disponible durante la promoción</p>
                    @elseif($plan->isFree() && $subscription?->plan_id !== $plan->id)
                    <form method="POST" action="{{ route('admin.subscription.change-plan') }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="w-full py-1.5 text-xs font-medium text-indigo-700 border border-indigo-300 rounded-lg hover:bg-indigo-50 transition">
                            Cambiar a este plan
                        </button>
                    </form>
                    @elseif(!$plan->isFree() && $subscription?->plan_id !== $plan->id)
                    <p class="text-xs text-center text-gray-400 italic">Contacta con el administrador</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
