<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Planes</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Gestión de planes de suscripción</p>
            </div>

            <a href="{{ route('superadmin.plans.create') }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo plan
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Listado de planes</h2>
                <span class="text-xs text-slate-400">{{ $plans->total() }} resultado(s)</span>
            </div>

            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Plan</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Precio/mes</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Usuarios</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Presup./mes</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Suscripciones</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($plans as $plan)
                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900">{{ $plan->name }}</p>
                                            <p class="mt-1 max-w-xs truncate text-xs text-slate-400">{{ $plan->description }}</p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="text-sm font-semibold text-slate-900">
                                            {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-700">
                                        {{ $plan->max_users }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-700">
                                        {{ $plan->max_presupuestos == 0 ? 'Ilimitado' : $plan->max_presupuestos }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-50 text-sm font-semibold text-indigo-700">
                                            {{ $plan->subscriptions_count }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $plan->active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $plan->active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            <a href="{{ route('superadmin.plans.edit', $plan) }}"
                                               class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('superadmin.plans.destroy', $plan) }}"
                                                  onsubmit="return confirm('¿Eliminar plan {{ addslashes($plan->name) }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1 rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-400">
                                        No hay planes creados.
                                        <a href="{{ route('superadmin.plans.create') }}" class="font-medium text-indigo-600 hover:underline">Crea el primero.</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-3 p-4 md:hidden">
                @forelse($plans as $plan)
                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $plan->name }}</p>

                            @if($plan->description)
                                <p class="mt-1 truncate text-xs text-slate-400">{{ $plan->description }}</p>
                            @endif

                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $plan->active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $plan->active ? 'Activo' : 'Inactivo' }}
                                </span>

                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $plan->isFree() ? 'Gratis' : '€' . number_format($plan->price_monthly, 2) . '/mes' }}
                                </span>

                                <span class="text-xs text-slate-500">
                                    {{ $plan->max_users }} usuarios
                                </span>

                                <span class="text-xs text-slate-500">
                                    {{ $plan->max_presupuestos == 0 ? '∞' : $plan->max_presupuestos }} presup./mes
                                </span>

                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                                    {{ $plan->subscriptions_count }} suscrip.
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('superadmin.plans.edit', $plan) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('superadmin.plans.destroy', $plan) }}"
                                  onsubmit="return confirm('¿Eliminar plan {{ addslashes($plan->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center text-sm text-slate-400 shadow-sm">
                        No hay planes creados.
                        <a href="{{ route('superadmin.plans.create') }}" class="font-medium text-indigo-600 hover:underline">Crea el primero.</a>
                    </div>
                @endforelse
            </div>

            @if($plans->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $plans->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>