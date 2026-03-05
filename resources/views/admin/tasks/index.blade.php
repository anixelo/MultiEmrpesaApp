<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tareas</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $company->name }}</p>
            </div>
            <a href="{{ route('admin.tasks.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Tarea
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tarea</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Asignado a</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Prioridad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Vencimiento</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @php
                    $statusColors  = ['pendiente'=>'yellow','en_progreso'=>'blue','completada'=>'green','cancelada'=>'red'];
                    $statusLabels  = ['pendiente'=>'Pendiente','en_progreso'=>'En progreso','completada'=>'Completada','cancelada'=>'Cancelada'];
                    $priorityColors = ['baja'=>'gray','media'=>'blue','alta'=>'orange','urgente'=>'red'];
                    $priorityLabels = ['baja'=>'Baja','media'=>'Media','alta'=>'Alta','urgente'=>'Urgente'];
                    @endphp
                    @forelse($tasks as $task)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $task->title }}</div>
                            @if($task->description)
                            <div class="text-xs text-gray-400 mt-0.5 max-w-xs truncate">{{ $task->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            @if($task->assignedUser)
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}
                                </div>
                                <span class="text-sm text-gray-700">{{ $task->assignedUser->name }}</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-400">Sin asignar</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColors[$task->status] }}-100 text-{{ $statusColors[$task->status] }}-800">
                                {{ $statusLabels[$task->status] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $priorityColors[$task->priority] }}-100 text-{{ $priorityColors[$task->priority] }}-800">
                                {{ $priorityLabels[$task->priority] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell text-sm text-gray-500">
                            @if($task->due_date)
                                {{ $task->due_date->format('d/m/Y') }}
                                @if($task->due_date->isPast() && $task->status !== 'completada')
                                <span class="text-red-500 text-xs font-medium ml-1">Vencida</span>
                                @endif
                            @else
                                <span class="text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tasks.edit', $task) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}"
                                      onsubmit="return confirm('¿Eliminar esta tarea?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No hay tareas creadas.
                            <a href="{{ route('admin.tasks.create') }}" class="text-indigo-600 hover:underline">Crea la primera.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $tasks->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
