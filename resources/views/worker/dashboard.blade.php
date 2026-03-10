<x-app-layout>
    @php
        $companyName = $company?->name ?? '';
        $canUseNotas = $notasEnabled ?? false;

        $estadoBadgeClasses = [
            'gray' => 'bg-gray-100 text-gray-700',
            'blue' => 'bg-blue-100 text-blue-700',
            'purple' => 'bg-purple-100 text-purple-700',
            'green' => 'bg-green-100 text-green-700',
            'red' => 'bg-red-100 text-red-700',
        ];

        $incidentStatusClasses = [
            'open' => 'bg-blue-100 text-blue-800',
            'in_review' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-orange-100 text-orange-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-gray-100 text-gray-600',
        ];

        $incidentPriorityClasses = [
            'baja' => 'bg-gray-100 text-gray-600',
            'media' => 'bg-blue-100 text-blue-800',
            'alta' => 'bg-orange-100 text-orange-800',
            'urgente' => 'bg-red-100 text-red-800',
        ];

        $presupuestoCards = [
            [
                'label' => 'Presupuestos totales',
                'value' => $presupuestoStats['total'] ?? 0,
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'iconWrap' => 'bg-violet-50',
                'iconColor' => 'text-violet-600',
            ],
            [
                'label' => 'Aceptados',
                'value' => $presupuestoStats['aceptados'] ?? 0,
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-green-50',
                'iconColor' => 'text-green-600',
            ],
            [
                'label' => 'Rechazados',
                'value' => $presupuestoStats['rechazados'] ?? 0,
                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-red-50',
                'iconColor' => 'text-red-600',
            ],
        ];

        $incidentCards = [
            [
                'label' => 'Total',
                'value' => $incidentStats['total'] ?? 0,
                'icon' => 'M18.364 5.636l-1.414 1.414M15 9l-3 3m0 0l-3 3m3-3l3 3m-3-3L9 9m9 3a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-gray-50',
                'iconColor' => 'text-gray-600',
            ],
            [
                'label' => 'Abiertas',
                'value' => $incidentStats['open'] ?? 0,
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-blue-50',
                'iconColor' => 'text-blue-600',
            ],
            [
                'label' => 'En progreso',
                'value' => $incidentStats['in_progress'] ?? 0,
                'icon' => 'M12 6v6l4 2',
                'iconWrap' => 'bg-orange-50',
                'iconColor' => 'text-orange-600',
            ],
            [
                'label' => 'Resueltas',
                'value' => $incidentStats['resolved'] ?? 0,
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-green-50',
                'iconColor' => 'text-green-600',
            ],
        ];
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel</h1>
                <p class="mt-1 text-sm text-gray-500">
                    Hola, {{ $user->name }}@if($companyName). {{ $companyName }}@endif
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">
                <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                Trabajador
            </span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-4 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

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
                @foreach($presupuestoCards as $card)
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

        @if($recentPresupuestos->isNotEmpty())
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
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
            </section>
        @endif

        @if($canUseNotas)
            <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-900">Últimas notas</h2>
                    <a href="{{ route('admin.notas.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                        Ver todas
                    </a>
                </div>

                @if($recentNotas->isNotEmpty())
                    <div class="divide-y divide-gray-50">
                        @foreach($recentNotas as $nota)
                            <div class="flex items-center justify-between gap-3 px-5 py-4">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-gray-900">{{ $nota->titulo }}</p>

                                    <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                        <span class="text-xs text-gray-500">
                                            {{ $nota->cliente?->nombre ?? '—' }}
                                        </span>

                                        @if($nota->presupuesto_id)
                                            <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">
                                                Con presupuesto
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <a
                                    href="{{ route('admin.notas.show', $nota->id) }}"
                                    class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                                >
                                    Ver
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-gray-400">No hay notas registradas.</p>
                        <a href="{{ route('admin.notas.create') }}" class="mt-2 inline-flex text-xs font-medium text-indigo-600 hover:underline">
                            Crear primera nota
                        </a>
                    </div>
                @endif
            </section>
        @endif

        <section class="space-y-4">
            <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
                @foreach($incidentCards as $card)
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

            @if($recentIncidents->isNotEmpty())
                <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                        <h2 class="text-base font-semibold text-gray-900">Mis últimas incidencias</h2>
                        <a href="{{ route('worker.incidents.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                            Ver todas
                        </a>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach($recentIncidents as $incident)
                            @php
                                $statusClass = $incidentStatusClasses[$incident->status] ?? 'bg-gray-100 text-gray-600';
                                $priorityClass = $incidentPriorityClasses[$incident->priority] ?? 'bg-gray-100 text-gray-600';
                            @endphp

                            <div class="flex items-center justify-between gap-4 px-6 py-4">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $incident->title }}</p>

                                    <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $statusClass }}">
                                            {{ $incident->status_label }}
                                        </span>

                                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $priorityClass }}">
                                            {{ $incident->priority_label }}
                                        </span>

                                        <span class="text-xs text-gray-400">
                                            {{ $incident->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('incidents.show', $incident) }}"
                                    class="inline-flex shrink-0 items-center gap-1 rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100"
                                >
                                    Ver
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </section>
    </div>
</x-app-layout>