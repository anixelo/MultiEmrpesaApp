<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.notas.show', $nota->id) }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">Editar Nota</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $nota->titulo }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                <form method="POST" action="{{ route('admin.notas.update', $nota->id) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                    <div class="rounded-md bg-red-50 p-4">
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- Client section --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Datos del cliente</h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente <span class="text-red-500">*</span></label>
                            <select name="cliente_id" required
                                    class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('cliente_id') border-red-400 @enderror">
                                <option value="">— Selecciona un cliente —</option>
                                @foreach($clientes as $c)
                                <option value="{{ $c->id }}" {{ old('cliente_id', $nota->cliente_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->nombre }}{{ $c->email ? ' — ' . $c->email : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('cliente_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- Note section --}}
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Notas</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
                                <input type="text" name="titulo" value="{{ old('titulo', $nota->titulo) }}" required
                                       class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('titulo') border-red-400 @enderror">
                                @error('titulo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
                                <textarea name="contenido" rows="10"
                                          class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('contenido', $nota->contenido) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('admin.notas.show', $nota->id) }}"
                           class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                            Guardar Cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
