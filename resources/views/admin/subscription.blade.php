<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Mi suscripción</h1>
            <p class="mt-0.5 text-sm text-slate-500">Gestiona el plan de tu empresa</p>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-4 text-sm text-rose-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        @php $company = auth()->user()->company; @endphp

        @if($company && $company->isInPromo() && $company->promoPlan())
            @php $promoPlan = $company->promoPlan(); @endphp

            <section class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white shadow-xl">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>
                    <div class="absolute -bottom-12 -left-10 h-36 w-36 rounded-full bg-white/10 blur-2xl"></div>
                </div>

                <div class="relative flex flex-col gap-5">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-white/20 shadow-inner">
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <h2 class="text-lg font-bold">Promoción activa</h2>
                            <p class="mt-1 text-sm text-indigo-100">
                                Estás disfrutando del plan
                                <strong class="text-white">{{ $promoPlan->name }}</strong>
                                de forma gratuita hasta el
                                <strong class="text-yellow-300">{{ $company->promo_ends_at->format('d \d\e F \d\e Y') }}</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="rounded-2xl bg-white/10 p-3 text-center">
                            <div class="text-xl font-bold">{{ $promoPlan->max_users }}</div>
                            <div class="text-xs text-indigo-200">Usuarios</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-3 text-center">
                            <div class="text-xl font-bold">{{ ($promoPlan->max_presupuestos ?? 0) == 0 ? '∞' : $promoPlan->max_presupuestos }}</div>
                            <div class="text-xs text-indigo-200">Presup./mes</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-3 text-center">
                            <div class="text-xl font-bold text-yellow-300">Gratis</div>
                            <div class="text-xs text-indigo-200">Precio actual</div>
                        </div>

                        <div class="rounded-2xl bg-white/10 p-3 text-center">
                            <div class="text-sm font-bold">{{ $company->promo_ends_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-indigo-200">Fin promoción</div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        @if(!$company || !$company->isInPromo())
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Plan actual</h2>
                    <span class="text-xs text-slate-400">Resumen de tu suscripción</span>
                </div>

                @if($subscription && $subscription->plan)
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 shadow-inner">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                            </svg>
                        </div>

                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3">
                                <h3 class="text-lg font-bold text-slate-900">{{ $subscription->plan->name }}</h3>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $subscription->isActive() ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $subscription->isActive() ? 'Activo' : ucfirst($subscription->status) }}
                                </span>
                            </div>

                            <p class="mt-1 text-sm text-slate-500">{{ $subscription->plan->description }}</p>

                            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                                <div class="rounded-2xl bg-slate-50 p-3 text-center">
                                    <div class="text-xl font-bold text-slate-900">{{ $subscription->plan->max_users }}</div>
                                    <div class="text-xs text-slate-500">Usuarios</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3 text-center">
                                    <div class="text-xl font-bold text-slate-900">{{ $subscription->plan->max_presupuestos == 0 ? '∞' : $subscription->plan->max_presupuestos }}</div>
                                    <div class="text-xs text-slate-500">Presup./mes</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3 text-center">
                                    <div class="text-xl font-bold text-slate-900">
                                        {{ $subscription->plan->isFree() ? 'Gratis' : '€' . number_format($subscription->plan->price_monthly, 2) }}
                                    </div>
                                    <div class="text-xs text-slate-500">Por mes</div>
                                </div>

                                <div class="rounded-2xl bg-slate-50 p-3 text-center">
                                    <div class="text-sm font-semibold text-slate-900">
                                        {{ $subscription->started_at?->format('d/m/Y') ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-500">Inicio</div>
                                </div>
                            </div>

                            @if($subscription->plan->features)
                                <div class="mt-4">
                                    <ul class="flex flex-wrap gap-2">
                                        @foreach($subscription->plan->features as $feature)
                                            <li class="inline-flex items-center gap-1 rounded-full bg-slate-50 px-3 py-1 text-xs text-slate-600 ring-1 ring-slate-200">
                                                <svg class="h-3.5 w-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                {{ $feature }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-5 py-8 text-center">
                        <svg class="mx-auto mb-3 h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138z"/>
                        </svg>
                        <p class="text-sm text-slate-400">No tienes ningún plan activo.</p>
                    </div>
                @endif
            </section>
        @endif

        @php $freePlans = $plans->where('price_monthly', 0); @endphp

        @if($freePlans->count())
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-5">
                    <h2 class="text-base font-semibold text-slate-900">Planes disponibles</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Puedes cambiar al plan gratuito directamente. Para planes de pago, contacta con el administrador.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($plans as $plan)
                        @php $isCurrentPlan = !$company->isInPromo() && ($subscription?->plan_id === $plan->id); @endphp

                        <article class="rounded-3xl border p-5 shadow-sm transition
                            {{ $isCurrentPlan ? 'border-indigo-300 bg-indigo-50 shadow-indigo-100' : 'border-slate-200 bg-white' }}">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <h3 class="font-semibold text-slate-900">{{ $plan->name }}</h3>

                                @if($isCurrentPlan)
                                    <span class="inline-flex rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                        Actual
                                    </span>
                                @endif
                            </div>

                            <p class="text-2xl font-bold text-slate-900">
                                {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) }}
                                @if(!$plan->isFree())
                                    <span class="text-sm font-normal text-slate-400">/mes</span>
                                @endif
                            </p>

                            <p class="mt-1 text-xs text-slate-500">
                                {{ $plan->max_users }} usuarios · {{ ($plan->max_presupuestos ?? 0) == 0 ? '∞' : $plan->max_presupuestos }} presup./mes
                            </p>

                            @if($plan->features)
                                <div class="mt-3 space-y-1.5">
                                    @foreach($plan->features as $feature)
                                        <div class="flex items-start gap-2 text-xs text-slate-600">
                                            <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-4">
                                @if($company->isInPromo() && $subscription?->plan_id !== $plan->id)
                                    <p class="text-center text-xs italic text-amber-600">No disponible durante la promoción</p>
                                @elseif($plan->isFree() && $subscription?->plan_id !== $plan->id)
                                    <form method="POST" action="{{ route('admin.subscription.change-plan') }}">
                                        @csrf
                                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                                        <button type="submit"
                                                class="w-full rounded-2xl border border-indigo-300 px-4 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-50">
                                            Cambiar a este plan
                                        </button>
                                    </form>
                                @elseif(!$plan->isFree() && $subscription?->plan_id !== $plan->id)
                                    <p class="text-center text-xs italic text-slate-400">Contacta con el administrador</p>
                                @else
                                    <div class="w-full rounded-2xl bg-slate-100 px-4 py-2 text-center text-xs font-medium text-slate-500">
                                        Plan actual
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-app-layout>