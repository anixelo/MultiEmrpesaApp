<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mis Tareas</h1>
                <p class="text-sm text-gray-500 mt-0.5">Hola, {{ $user->name }}. Aquí están tus tareas asignadas.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                Trabajador
            </span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
            $statCards = [
                ['label'=>'Total','value'=>$stats['total'],'color'=>'gray'],
                ['label'=>'Pendientes','value'=>$stats['pendiente'],'color'=>'yellow'],
                ['label'=>'En progreso','value'=>$stats['en_progreso'],'color'=>'blue'],
                ['label'=>'Completadas','value'=>$stats['completada'],'color'=>'green'],
            ];
            @endphp
            @foreach($statCards as $sc)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 text-center">
                <p class="text-2xl font-bold text-gray-900">{{ $sc['value'] }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $sc['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Tasks list --}}
        <div class="space-y-3">
            @forelse($tasks as $task)
            @php
            $priorityColors = ['baja'=>'gray','media'=>'blue','alta'=>'orange','urgente'=>'red'];
            $statusColors   = ['pendiente'=>'yellow','en_progreso'=>'blue','completada'=>'green','cancelada'=>'red'];
            $statusLabels   = ['pendiente'=>'Pendiente','en_progreso'=>'En progreso','completada'=>'Completada','cancelada'=>'Cancelada'];
            $priorityLabels = ['baja'=>'Baja','media'=>'Media','alta'=>'Alta','urgente'=>'Urgente'];
            @endphp
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
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

                    {{-- Status update form --}}
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
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <p class="text-gray-500 font-medium">No tienes tareas asignadas</p>
                <p class="text-gray-400 text-sm mt-1">Cuando te asignen tareas, aparecerán aquí.</p>
            </div>
            @endforelse
        </div>

        @if($tasks->hasPages())
        <div>{{ $tasks->links() }}</div>
        @endif
    </div>
</x-app-layout>
