<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('worker.incidents.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Nueva Incidencia</h1>
                <p class="text-sm text-gray-500 mt-0.5">Describe el problema para que podamos ayudarte</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        @if(session('error'))
        <div class="mb-4 rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('worker.incidents.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-400 @enderror"
                           placeholder="Breve descripción del problema">
                    @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción detallada <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="5"
                              class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-400 @enderror"
                              placeholder="Describe el problema con el mayor detalle posible: qué ocurrió, cuándo, qué pasos llevaste a cabo...">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioridad <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['baja' => ['label' => 'Baja', 'color' => 'gray'], 'media' => ['label' => 'Media', 'color' => 'blue'], 'alta' => ['label' => 'Alta', 'color' => 'orange'], 'urgente' => ['label' => 'Urgente', 'color' => 'red']] as $value => $meta)
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="priority" value="{{ $value }}" {{ old('priority', 'media') === $value ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-full py-2.5 text-center text-sm font-medium rounded-xl border-2 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 border-gray-200 text-gray-600 hover:border-gray-300 transition">
                                {{ $meta['label'] }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('priority')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <a href="{{ route('worker.incidents.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                        Enviar Incidencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
