<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel</h1>
                <p class="text-sm text-gray-500 mt-0.5">Hola, {{ $user->name }}. {{ $company ? $company->name : '' }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Trabajador
            </span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        {{-- Quick actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.presupuestos.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Nuevo Presupuesto
            </a>
            @if($notasEnabled)
            <a href="{{ route('admin.notas.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Nueva Nota
            </a>
            @endif
        </div>



        {{-- Presupuesto stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            @php
            $presupuestoCards = [
                ['label' => 'Presupuestos totales', 'value' => $presupuestoStats['total'] ?? 0, 'color' => 'violet'],
                ['label' => 'Aceptados', 'value' => $presupuestoStats['aceptados'] ?? 0, 'color' => 'green'],
                ['label' => 'Rechazados', 'value' => $presupuestoStats['rechazados'] ?? 0, 'color' => 'red'],
            ];
            @endphp
            @foreach($presupuestoCards as $pc)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $pc['value'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $pc['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Recent presupuestos --}}
        @if($recentPresupuestos->isNotEmpty())
        @php
        $colorMap = [
            'gray'   => 'bg-gray-100 text-gray-700',
            'blue'   => 'bg-blue-100 text-blue-700',
            'purple' => 'bg-purple-100 text-purple-700',
            'green'  => 'bg-green-100 text-green-700',
            'red'    => 'bg-red-100 text-red-700',
        ];
        @endphp
        <div class="col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Últimos presupuestos</h2>
                <a href="{{ route('admin.presupuestos.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todos</a>
            </div>
            {{-- Desktop table --}}
            <div class="hidden sm:block overflow-x-auto">
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
                        @php $badgeClass = $colorMap[$p->estado_color] ?? 'bg-gray-100 text-gray-700'; @endphp
                        <tr>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ $p->numero }}</td>
                            <td class="px-5 py-3 text-sm text-gray-600 whitespace-nowrap">{{ $p->cliente?->nombre ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $p->fecha->format('d/m/Y') }}</td>
                            <td class="px-5 py-3 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeClass }}">{{ $p->estado_label }}</span>
                            </td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900 text-right whitespace-nowrap">{{ number_format($p->total, 2, ',', '.') }} €</td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.presupuestos.show', $p->id) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-medium">Ver</a>
                                    <a href="{{ route('admin.presupuestos.edit', $p->id) }}" class="text-xs text-yellow-600 hover:text-yellow-900 font-medium">Editar</a>
                                    <a href="{{ route('admin.presupuestos.pdf', $p->id) }}" class="text-xs text-red-600 hover:text-red-900 font-medium">PDF</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Mobile list --}}
            <div class="sm:hidden divide-y divide-gray-50">
                @foreach($recentPresupuestos as $p)
                @php $badgeClass = $colorMap[$p->estado_color] ?? 'bg-gray-100 text-gray-700'; @endphp
                <div class="px-5 py-4 flex items-center justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-sm font-semibold text-gray-900">{{ $p->numero }}</span>
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-semibold {{ $badgeClass }}">{{ $p->estado_label }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $p->cliente?->nombre ?? '—' }} · {{ $p->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div class="shrink-0 flex gap-2">
                        <a href="{{ route('admin.presupuestos.show', $p->id) }}" class="text-xs text-indigo-600 font-medium">Ver</a>
                        <a href="{{ route('admin.presupuestos.pdf', $p->id) }}" class="text-xs text-red-600 font-medium">PDF</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Notas section (only if enabled) --}}
        @if($notasEnabled)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Últimas notas</h2>
                <a href="{{ route('admin.notas.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
            </div>
            @if($recentNotas->isNotEmpty())
            <div class="divide-y divide-gray-50">
                @foreach($recentNotas as $nota)
                <div class="px-5 py-4 flex items-center justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $nota->titulo }}</p>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            <span class="text-xs text-gray-500">{{ $nota->cliente?->nombre ?? '—' }}</span>
                            @if($nota->presupuesto_id)
                                <span class="inline-flex px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">Con presupuesto</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.notas.show', $nota->id) }}"
                       class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        Ver
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="px-6 py-8 text-center">
                <p class="text-sm text-gray-400">No hay notas registradas.</p>
                <a href="{{ route('admin.notas.create') }}" class="mt-2 inline-flex text-xs text-indigo-600 hover:underline">Crear primera nota</a>
            </div>
            @endif
        </div>
        @endif



        {{-- Incident stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
            $incidentCards = [
                ['label' => 'Total', 'value' => $incidentStats['total'], 'color' => 'gray'],
                ['label' => 'Abiertas', 'value' => $incidentStats['open'], 'color' => 'blue'],
                ['label' => 'En progreso', 'value' => $incidentStats['in_progress'], 'color' => 'orange'],
                ['label' => 'Resueltas', 'value' => $incidentStats['resolved'], 'color' => 'green'],
            ];
            @endphp
            @foreach($incidentCards as $card)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Recent incidents --}}
        @if($recentIncidents->isNotEmpty())
        <div class="col-span-3 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Mis últimas incidencias</h2>
                <a href="{{ route('worker.incidents.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentIncidents as $incident)
                @php
                $statusColors = ['open'=>'bg-blue-100 text-blue-800','in_review'=>'bg-yellow-100 text-yellow-800','in_progress'=>'bg-orange-100 text-orange-800','resolved'=>'bg-green-100 text-green-800','closed'=>'bg-gray-100 text-gray-600'];
                $priorityColors = ['baja'=>'bg-gray-100 text-gray-600','media'=>'bg-blue-100 text-blue-800','alta'=>'bg-orange-100 text-orange-800','urgente'=>'bg-red-100 text-red-800'];
                @endphp
                <div class="px-6 py-3 flex items-center justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $incident->title }}</p>
                        <div class="flex flex-wrap gap-1.5 mt-1">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$incident->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $incident->status_label }}
                            </span>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$incident->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $incident->priority_label }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $incident->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('incidents.show', $incident) }}"
                       class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        Ver
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</x-app-layout>
