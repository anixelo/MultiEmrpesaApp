<x-app-layout>
    @php
        $hasCompany = filled($company);

        $companyName = $hasCompany ? $company->name : 'Sin empresa asignada';
        $hasPromo = $hasCompany && $company->isInPromo() && $company->promoPlan();
        $promoPlan = $hasPromo ? $company->promoPlan() : null;

        $canUseNotas = $hasCompany && $company->canUseNotas();
        $hasEmpresas = $hasCompany ? $company->empresas()->exists() : false;

        $presupuestoStatusConfig = [
            'borrador' => [
                'label' => 'Borrador',
                'bar' => 'bg-gray-400',
            ],
            'enviado' => [
                'label' => 'Enviado',
                'bar' => 'bg-blue-500',
            ],
            'visto' => [
                'label' => 'Visto',
                'bar' => 'bg-purple-500',
            ],
            'aceptado' => [
                'label' => 'Aceptado',
                'bar' => 'bg-green-500',
            ],
            'rechazado' => [
                'label' => 'Rechazado',
                'bar' => 'bg-red-500',
            ],
        ];

        $estadoBadgeClasses = [
            'gray' => 'bg-gray-100 text-gray-700',
            'blue' => 'bg-blue-100 text-blue-700',
            'purple' => 'bg-purple-100 text-purple-700',
            'green' => 'bg-green-100 text-green-700',
            'red' => 'bg-red-100 text-red-700',
        ];

        $statCards = [
            [
                'label' => 'Presupuestos totales',
                'value' => $stats['total_presupuestos'] ?? 0,
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'iconWrap' => 'bg-violet-50',
                'iconColor' => 'text-violet-600',
            ],
            [
                'label' => 'Presupuestos aceptados',
                'value' => $stats['presupuestos_aceptados'] ?? 0,
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
            ],
            [
                'label' => 'Presupuestos rechazados',
                'value' => $stats['presupuestos_rechazados'] ?? 0,
                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-red-50',
                'iconColor' => 'text-red-600',
            ],
        ];

        $notaCards = [
            [
                'label' => 'Notas totales',
                'value' => $stats['total_notas'] ?? 0,
                'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                'iconWrap' => 'bg-amber-50',
                'iconColor' => 'text-amber-600',
            ],
            [
                'label' => 'Con presupuesto',
                'value' => $stats['notas_con_presupuesto'] ?? 0,
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
            ],
            [
                'label' => 'Sin presupuesto',
                'value' => $stats['notas_sin_presupuesto'] ?? 0,
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-orange-50',
                'iconColor' => 'text-orange-600',
            ],
        ];

        $totalPresupuestos = max(($presupuestosByStatus ?? collect())->sum(), 1);
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $companyName }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                Administrador
            </span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @unless($hasCompany)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800">
                <span class="font-semibold">Atención:</span>
                No tienes una empresa asignada. Contacta con el superadministrador.
            </div>
        @else
            @if($hasPromo)
                <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white shadow-lg">


                    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center">


                        <div class="flex-1">
                            <p class="text-base font-bold">
                                Estás disfrutando del plan
                                <span class="text-yellow-300">{{ $promoPlan->name }}</span>
                                de forma gratuita
                            </p>
                            <p class="mt-1 text-sm text-indigo-100">
                                Tu promoción es válida hasta el
                                <strong>{{ $company->promo_ends_at->format('d \d\e F \d\e Y') }}</strong>.
                                ¡Aprovéchalo al máximo!
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.subscription') }}"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white/20 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/30"
                        >
                            Ver suscripción
                        </a>
                    </div>
                </section>
            @endif

            @unless($hasEmpresas)
                <section class="flex flex-col gap-4 rounded-2xl border border-blue-200 bg-blue-50 p-6 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="font-semibold text-gray-900">
                            Registra tu empresa para comenzar a crear presupuestos
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Necesitas al menos una empresa registrada para poder gestionar presupuestos, clientes y servicios.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.empresas.create') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        Crear empresa
                    </a>
                </section>
            @endunless

            <section class="space-y-4">
                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.presupuestos.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                    >
                        <svg class="h-4 w-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Nuevo presupuesto
                    </a>

                    @if($canUseNotas)
                        <a
                            href="{{ route('admin.notas.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >
                            <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Nueva nota
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($statCards as $card)
                        <article class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                            <div class="rounded-xl p-3 {{ $card['iconWrap'] }}">
                                <svg class="h-6 w-6 {{ $card['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">{{ $card['label'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6">
                <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Presupuestos por estado</h2>
                        <span class="text-xs text-gray-400">Resumen general</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($presupuestoStatusConfig as $estado => $config)
                            @php
                                $count = $presupuestosByStatus[$estado] ?? 0;
                                $percentage = round(($count / $totalPresupuestos) * 100);
                            @endphp

                            <div>
                                <div class="mb-1.5 flex items-center justify-between text-xs">
                                    <span class="text-gray-600">{{ $config['label'] }}</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $count }} <span class="text-gray-400">({{ $percentage }}%)</span>
                                    </span>
                                </div>

                                <div class="h-2 w-full rounded-full bg-gray-100">
                                    <div
                                        class="h-2 rounded-full transition-all duration-500 {{ $config['bar'] }}"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                @if($recentPresupuestos->isNotEmpty())
                    <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Últimos presupuestos</h2>
                            <a href="{{ route('admin.presupuestos.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                                Ver todos
                            </a>
                        </div>

                        <div class="hidden overflow-x-auto sm:block">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Número</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 bg-white">
                                    @foreach($recentPresupuestos as $p)
                                        @php
                                            $badgeClass = $estadoBadgeClasses[$p->estado_color] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <tr class="hover:bg-gray-50/60">
                                            <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-gray-900">
                                                {{ $p->numero }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                                {{ $p->cliente?->nombre ?? '—' }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-500">
                                                {{ $p->fecha->format('d/m/Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3">
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                    {{ $p->estado_label }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-right text-sm font-medium text-gray-900">
                                                {{ number_format($p->total, 2, ',', '.') }} €
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('admin.presupuestos.show', $p->id) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900">Ver</a>
                                                    <a href="{{ route('admin.presupuestos.edit', $p->id) }}" class="text-xs font-medium text-amber-600 hover:text-amber-900">Editar</a>
                                                    <a href="{{ route('admin.presupuestos.pdf', $p->id) }}" class="text-xs font-medium text-red-600 hover:text-red-900">PDF</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divide-y divide-gray-50 sm:hidden">
                            @foreach($recentPresupuestos as $p)
                                @php
                                    $badgeClass = $estadoBadgeClasses[$p->estado_color] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <div class="flex items-center justify-between gap-3 px-5 py-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="text-sm font-semibold text-gray-900">{{ $p->numero }}</span>
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeClass }}">
                                                {{ $p->estado_label }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $p->cliente?->nombre ?? '—' }} · {{ $p->fecha->format('d/m/Y') }}
                                        </p>
                                        <p class="mt-1 text-sm font-medium text-gray-900">
                                            {{ number_format($p->total, 2, ',', '.') }} €
                                        </p>
                                    </div>

                                    <div class="flex shrink-0 flex-col items-end gap-1">
                                        <a href="{{ route('admin.presupuestos.show', $p->id) }}" class="text-xs font-medium text-indigo-600">Ver</a>
                                        <a href="{{ route('admin.presupuestos.pdf', $p->id) }}" class="text-xs font-medium text-red-600">PDF</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif

                @if($canUseNotas)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($notaCards as $card)
                            <article class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                                <div class="rounded-xl p-3 {{ $card['iconWrap'] }}">
                                    <svg class="h-6 w-6 {{ $card['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $card['label'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if($canUseNotas && isset($recentNotas) && $recentNotas->isNotEmpty())
                    <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Últimas notas</h2>
                            <a href="{{ route('admin.notas.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                                Ver todas
                            </a>
                        </div>

                        <div class="hidden overflow-x-auto sm:block">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Título</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Presupuesto</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 bg-white">
                                    @foreach($recentNotas as $n)
                                        <tr class="hover:bg-gray-50/60">
                                            <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-gray-900">
                                                {{ $n->titulo }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                                {{ $n->cliente?->nombre ?? '—' }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3">
                                                @if($n->presupuesto_id)
                                                    <span class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                                        Con presupuesto
                                                    </span>
                                                @else
                                                    <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                                                        Sin presupuesto
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-500">
                                                {{ $n->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-3">
                                                    <a href="{{ route('admin.notas.show', $n->id) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900">Ver</a>
                                                    <a href="{{ route('admin.notas.edit', $n->id) }}" class="text-xs font-medium text-amber-600 hover:text-amber-900">Editar</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divide-y divide-gray-50 sm:hidden">
                            @foreach($recentNotas as $n)
                                <div class="flex items-center justify-between gap-3 px-5 py-4">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $n->titulo }}</p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ $n->cliente?->nombre ?? '—' }} · {{ $n->created_at->format('d/m/Y') }}
                                        </p>
                                    </div>

                                    <a href="{{ route('admin.notas.show', $n->id) }}" class="shrink-0 text-xs font-medium text-indigo-600">
                                        Ver
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif
            </section>
        @endunless
















    </div>




</x-app-layout>