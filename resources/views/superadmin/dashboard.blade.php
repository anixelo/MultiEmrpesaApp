<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Panel</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Visión global del sistema</p>
            </div>

            <span class="inline-flex shrink-0 items-center gap-2 rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-800 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-violet-500 animate-pulse"></span>
                Superadmin
            </span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @php
            $cards = [
                [
                    'label' => 'Usuarios totales',
                    'value' => $stats['total_users'],
                    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                    'iconWrap' => 'bg-indigo-50',
                    'iconColor' => 'text-indigo-600',
                ],
                [
                    'label' => 'Cuentas activas',
                    'value' => $stats['active_companies'],
                    'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                    'iconWrap' => 'bg-emerald-50',
                    'iconColor' => 'text-emerald-600',
                ],
                [
                    'label' => 'Total cuentas',
                    'value' => $stats['total_companies'],
                    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                    'iconWrap' => 'bg-sky-50',
                    'iconColor' => 'text-sky-600',
                ],
            ];
        @endphp

        <div class="grid grid-cols-3 gap-2 md:grid-cols-3 md:gap-4">
            @foreach($cards as $card)
                <div class="group flex flex-col items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-3 text-center shadow-sm ring-1 ring-transparent transition duration-200 hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-lg hover:ring-indigo-100 md:flex md:flex-row md:items-center md:justify-between md:gap-4 md:rounded-3xl md:px-5 md:py-4 md:text-left">
                    <div class="flex flex-col items-center gap-2 md:flex md:flex-row md:items-center md:gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $card['iconWrap'] }} shadow-inner md:h-12 md:w-12">
                            <svg class="h-5 w-5 {{ $card['iconColor'] }} md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <p class="text-[10px] font-medium leading-tight text-slate-500 md:text-xs md:mt-0.5">{{ $card['label'] }}</p>
                            <p class="text-lg font-bold text-slate-900 md:text-2xl md:font-bold">{{ $card['value'] }}</p>
                        </div>
                    </div>

                    <div class="hidden md:flex h-10 w-10 items-center justify-center rounded-2xl bg-slate-50 text-slate-400 transition group-hover:bg-indigo-50 group-hover:text-indigo-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.companies.create') }}"
               class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva cuenta
            </a>

            <a href="{{ route('superadmin.users.create') }}"
               class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Nuevo usuario
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Últimos usuarios</h2>
                    <a href="{{ route('superadmin.users.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">Ver todos</a>
                </div>

                @if($recentUsers->isNotEmpty())
                    <div class="hidden md:block">
                        <div class="overflow-x-auto px-4 pb-4 sm:px-6">
                            <table class="min-w-full border-separate border-spacing-y-3">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Usuario</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Email</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Rol</th>
                                        <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentUsers as $user)
                                        <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                            <td class="rounded-l-2xl px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                    <span class="text-sm font-semibold text-slate-900">{{ $user->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                            <td class="px-4 py-4">
                                                @if($user->roles->first())
                                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                                        {{ $user->roles->first()->name }}
                                                    </span>
                                                @else
                                                    <span class="text-sm text-slate-400">—</span>
                                                @endif
                                            </td>
                                            <td class="rounded-r-2xl px-4 py-4 text-right">
                                                <a href="{{ route('superadmin.users.edit', $user) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-3 p-4 md:hidden">
                        @foreach($recentUsers as $user)
                            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                            <p class="truncate text-xs text-slate-500">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    @if($user->roles->first())
                                        <span class="inline-flex shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">
                                            {{ $user->roles->first()->name }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 flex justify-end border-t border-slate-100 pt-3">
                                    <a href="{{ route('superadmin.users.edit', $user) }}"
                                       class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                        Editar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-slate-400">No hay usuarios aún.</p>
                    </div>
                @endif
            </section>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Últimas cuentas</h2>
                    <a href="{{ route('superadmin.companies.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">Ver todas</a>
                </div>

                @if($recentCompanies->isNotEmpty())
                    <div class="hidden md:block">
                        <div class="overflow-x-auto px-4 pb-4 sm:px-6">
                            <table class="min-w-full border-separate border-spacing-y-3">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Cuenta</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Usuarios</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                        <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentCompanies as $company)
                                        <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                            <td class="rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                                {{ $company->name }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-slate-600">
                                                {{ $company->users_count }} usuario(s)
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $company->active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                                    {{ $company->active ? 'Activa' : 'Inactiva' }}
                                                </span>
                                            </td>
                                            <td class="rounded-r-2xl px-4 py-4 text-right">
                                                <a href="{{ route('superadmin.companies.edit', $company) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Editar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="space-y-3 p-4 md:hidden">
                        @foreach($recentCompanies as $company)
                            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900">{{ $company->name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $company->users_count }} usuario(s)</p>
                                    </div>

                                    <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-medium {{ $company->active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $company->active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>

                                <div class="mt-3 flex justify-end border-t border-slate-100 pt-3">
                                    <a href="{{ route('superadmin.companies.edit', $company) }}"
                                       class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                        Editar
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-slate-400">No hay cuentas aún.</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>