<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel de Trabajador</h1>
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
            <a href="{{ route('worker.incidents.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Incidencia
            </a>
            <a href="{{ route('worker.incidents.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Mis Incidencias
            </a>
            <a href="{{ route('admin.presupuestos.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Nuevo Presupuesto
            </a>
            <a href="{{ route('admin.presupuestos.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                Presupuestos
            </a>
            <a href="{{ route('admin.clientes.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Clientes
            </a>
        </div>

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
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
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

        {{-- Tasks section (only if enabled) --}}
        @if($tasksEnabled)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-semibold text-gray-900 mb-4">Mis Tareas Asignadas</h2>

            {{-- Task stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                @php
                $tCards = [
                    ['label' => 'Total', 'value' => $taskStats['total'], 'color' => 'gray'],
                    ['label' => 'Pendientes', 'value' => $taskStats['pendiente'], 'color' => 'yellow'],
                    ['label' => 'En progreso', 'value' => $taskStats['en_progreso'], 'color' => 'blue'],
                    ['label' => 'Completadas', 'value' => $taskStats['completada'], 'color' => 'green'],
                ];
                @endphp
                @foreach($tCards as $tc)
                <div class="bg-gray-50 rounded-xl p-3 text-center">
                    <p class="text-xl font-bold text-gray-900">{{ $tc['value'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $tc['label'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Tasks list --}}
            <div class="space-y-3">
                @forelse($tasks as $task)
                @php
                $priorityColors = ['baja' => 'gray', 'media' => 'blue', 'alta' => 'orange', 'urgente' => 'red'];
                $statusColors   = ['pendiente' => 'yellow', 'en_progreso' => 'blue', 'completada' => 'green', 'cancelada' => 'red'];
                $statusLabels   = ['pendiente' => 'Pendiente', 'en_progreso' => 'En progreso', 'completada' => 'Completada', 'cancelada' => 'Cancelada'];
                $priorityLabels = ['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta', 'urgente' => 'Urgente'];
                @endphp
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColors[$task->status] }}-100 text-{{ $statusColors[$task->status] }}-800">
                                    {{ $statusLabels[$task->status] }}
                                </span>
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $priorityColors[$task->priority] }}-100 text-{{ $priorityColors[$task->priority] }}-800">
                                    {{ $priorityLabels[$task->priority] }}
                                </span>
                                @if($task->due_date)
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $task->due_date->format('d/m/Y') }}
                                    @if($task->due_date->isPast() && $task->status !== 'completada')
                                    <span class="text-red-500 font-medium">Vencida</span>
                                    @endif
                                </span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-gray-900">{{ $task->title }}</h3>
                            @if($task->description)
                            <p class="text-sm text-gray-500 mt-1">{{ $task->description }}</p>
                            @endif
                        </div>
                        @if($task->status !== 'completada' && $task->status !== 'cancelada')
                        <form method="POST" action="{{ route('worker.tasks.update-status', $task) }}" class="shrink-0">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                    class="text-xs border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="pendiente"   {{ $task->status === 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_progreso" {{ $task->status === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                                <option value="completada"  {{ $task->status === 'completada'  ? 'selected' : '' }}>Completada</option>
                            </select>
                        </form>
                        @else
                        <span class="text-xs text-gray-400 italic shrink-0">{{ $statusLabels[$task->status] }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-4">No tienes tareas asignadas.</p>
                @endforelse
            </div>

            @if($tasks->hasPages())
            <div class="mt-4">{{ $tasks->links() }}</div>
            @endif
        </div>
        @endif

    </div>
</x-app-layout>
