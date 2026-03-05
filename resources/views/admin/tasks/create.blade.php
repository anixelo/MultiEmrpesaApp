<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tasks.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nueva Tarea</h1>
                <p class="text-sm text-gray-500 mt-0.5">Crea una tarea y asígnala a un miembro del equipo</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('admin.tasks.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-400 @enderror"
                           placeholder="Ej. Revisar informe mensual">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Descripción detallada de la tarea">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asignar a</label>
                    <select name="assigned_to"
                            class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('assigned_to') border-red-400 @enderror">
                        <option value="">Sin asignar</option>
                        @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" {{ old('assigned_to') == $worker->id ? 'selected' : '' }}>
                            {{ $worker->name }} ({{ $worker->roles->first()?->name ?? '—' }})
                        </option>
                        @endforeach
                    </select>
                    @error('assigned_to')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                        <select name="status"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pendiente"   {{ old('status', 'pendiente') === 'pendiente'   ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_progreso" {{ old('status') === 'en_progreso' ? 'selected' : '' }}>En progreso</option>
                            <option value="completada"  {{ old('status') === 'completada'  ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada"   {{ old('status') === 'cancelada'   ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prioridad <span class="text-red-500">*</span></label>
                        <select name="priority"
                                class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="baja"    {{ old('priority') === 'baja'    ? 'selected' : '' }}>Baja</option>
                            <option value="media"   {{ old('priority', 'media') === 'media'   ? 'selected' : '' }}>Media</option>
                            <option value="alta"    {{ old('priority') === 'alta'    ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ old('priority') === 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha límite</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('admin.tasks.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Crear Tarea
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
