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

        {{-- Current plan --}}
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

        {{-- Available free plans --}}
        @php $freePlans = $plans->where('price_monthly', 0); @endphp
        @if($freePlans->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-1">Planes Disponibles</h2>
            <p class="text-sm text-gray-500 mb-4">Puedes cambiar al plan gratuito directamente. Para planes de pago, contacta con el administrador.</p>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($plans as $plan)
                <div class="border border-gray-200 rounded-xl p-4 {{ ($subscription?->plan_id === $plan->id) ? 'border-indigo-300 bg-indigo-50' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-gray-900">{{ $plan->name }}</h3>
                        @if($subscription?->plan_id === $plan->id)
                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">Actual</span>
                        @endif
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-1">
                        {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) }}
                        @if(!$plan->isFree())<span class="text-sm font-normal text-gray-400">/mes</span>@endif
                    </p>
                    <p class="text-xs text-gray-500 mb-3">{{ $plan->max_users }} usuarios · {{ $plan->max_incidents }} incidencias</p>
                    @if($plan->isFree() && $subscription?->plan_id !== $plan->id)
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
