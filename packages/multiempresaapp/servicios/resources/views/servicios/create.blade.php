<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Nuevo Servicio
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('admin.servicios.store') }}">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label for="nombre" class="block text-sm font-medium text-gray-700">
                                Nombre <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre') }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nombre') border-red-300 @enderror">
                            @error('nombre')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-4">
                            <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                            <textarea id="descripcion"
                                      name="descripcion"
                                      rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('descripcion') border-red-300 @enderror">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Precio --}}
                        <div class="mb-4">
                            <label for="precio" class="block text-sm font-medium text-gray-700">
                                Precio (€) <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   id="precio"
                                   name="precio"
                                   value="{{ old('precio') }}"
                                   required
                                   min="0"
                                   step="0.01"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('precio') border-red-300 @enderror">
                            @error('precio')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- IVA --}}
                        <div class="mb-4">
                            <label for="iva_tipo" class="block text-sm font-medium text-gray-700">Tipo de IVA</label>
                            <select id="iva_tipo"
                                    name="iva_tipo"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('iva_tipo') border-red-300 @enderror">
                                <option value="" {{ old('iva_tipo') === null || old('iva_tipo') === '' ? 'selected' : '' }}>Sin IVA específico</option>
                                <option value="0"  {{ old('iva_tipo') == '0'  ? 'selected' : '' }}>0%</option>
                                <option value="4"  {{ old('iva_tipo') == '4'  ? 'selected' : '' }}>4%</option>
                                <option value="10" {{ old('iva_tipo') == '10' ? 'selected' : '' }}>10%</option>
                                <option value="21" {{ old('iva_tipo') == '21' ? 'selected' : '' }}>21%</option>
                            </select>
                            @error('iva_tipo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Orden --}}
                        <div class="mb-4">
                            <label for="orden" class="block text-sm font-medium text-gray-700">Orden</label>
                            <input type="number"
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('orden') border-red-300 @enderror">
                            @error('orden')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Activo --}}
                        <div class="mb-6 flex items-center gap-2">
                            <input type="checkbox"
                                   id="activo"
                                   name="activo"
                                   value="1"
                                   {{ old('activo', '1') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="activo" class="text-sm font-medium text-gray-700">Activo</label>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Guardar
                            </button>
                            <a href="{{ route('admin.servicios.index') }}"
                               class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
