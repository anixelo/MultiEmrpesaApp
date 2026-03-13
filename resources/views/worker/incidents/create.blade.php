<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('worker.incidents.index') }}" class="text-slate-400 transition hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div>
                <h1 class="text-2xl font-bold text-slate-900">Nueva incidencia</h1>
                <p class="mt-0.5 text-sm text-slate-500">Describe el problema para que podamos ayudarte</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">

        @if(session('error'))
            <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Formulario de incidencia</h2>
                <p class="mt-1 text-xs text-slate-400">Cuanta más información incluyas, más fácil será revisarla</p>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('worker.incidents.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">
                            Título <span class="text-rose-500">*</span>
                        </label>

                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               class="w-full rounded-2xl border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-rose-400 @enderror"
                               placeholder="Breve descripción del problema">

                        @error('title')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">
                            Descripción detallada <span class="text-rose-500">*</span>
                        </label>

                        <textarea name="description"
                                  rows="5"
                                  class="w-full rounded-2xl border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-rose-400 @enderror"
                                  placeholder="Describe el problema con el mayor detalle posible: qué ocurrió, cuándo, qué pasos llevaste a cabo...">{{ old('description') }}</textarea>

                        @error('description')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">
                            Prioridad <span class="text-rose-500">*</span>
                        </label>

                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                            @foreach([
                                'baja' => ['label' => 'Baja'],
                                'media' => ['label' => 'Media'],
                                'alta' => ['label' => 'Alta'],
                                'urgente' => ['label' => 'Urgente']
                            ] as $value => $meta)
                                <label class="relative flex cursor-pointer">
                                    <input type="radio"
                                           name="priority"
                                           value="{{ $value }}"
                                           {{ old('priority', 'media') === $value ? 'checked' : '' }}
                                           class="peer sr-only">

                                    <div class="w-full rounded-2xl border-2 border-slate-200 py-2.5 text-center text-sm font-medium text-slate-600 transition hover:border-slate-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700">
                                        {{ $meta['label'] }}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('priority')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-2">
                        <a href="{{ route('worker.incidents.index') }}"
                           class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                            Enviar incidencia
                        </button>
                    </div>
                </form>
            </div>
        </article>
    </div>
</x-app-layout>