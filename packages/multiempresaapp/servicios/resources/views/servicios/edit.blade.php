<x-app-layout>

    
    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.servicios.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Editar concepto</h1>
                <p class="mt-1 truncate text-sm text-slate-500">{{$servicio->nombre }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('admin.servicios.update', $servicio) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-slate-700">
                                Nombre <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre', $servicio->nombre) }}"
                                   required
                                   class="mt-1 block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-rose-300 @enderror">
                            @error('nombre')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipo --}}
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-slate-700">Tipo</label>
                            <select id="tipo"
                                    name="tipo"
                                    class="mt-1 block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('tipo') border-rose-300 @enderror">
                                <option value="servicio" {{ old('tipo', $servicio->tipo) === 'servicio' ? 'selected' : '' }}>Servicio</option>
                                <option value="producto" {{ old('tipo', $servicio->tipo) === 'producto' ? 'selected' : '' }}>Producto</option>
                                <option value="otro" {{ old('tipo', $servicio->tipo) === 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('tipo')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-slate-700">Descripción</label>
                            <textarea id="descripcion"
                                      name="descripcion"
                                      rows="3"
                                      class="mt-1 block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('descripcion') border-rose-300 @enderror">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                            @error('descripcion')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Precio --}}
                        <div>
                            <label for="precio" class="block text-sm font-medium text-slate-700">
                                Precio (€) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number"
                                   id="precio"
                                   name="precio"
                                   value="{{ old('precio', $servicio->precio) }}"
                                   required
                                   min="0"
                                   step="0.01"
                                   class="mt-1 block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('precio') border-rose-300 @enderror">
                            @error('precio')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- IVA --}}
                        <div>
                            <label for="iva_tipo" class="block text-sm font-medium text-slate-700">Tipo de IVA</label>
                            <select id="iva_tipo"
                                    name="iva_tipo"
                                    class="mt-1 block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('iva_tipo') border-rose-300 @enderror">
                                <option value="" {{ old('iva_tipo', $servicio->iva_tipo) === null || old('iva_tipo', $servicio->iva_tipo) === '' ? 'selected' : '' }}>Sin IVA específico</option>
                                <option value="0" {{ (string) old('iva_tipo', $servicio->iva_tipo) === '0' ? 'selected' : '' }}>0%</option>
                                <option value="4" {{ (string) old('iva_tipo', $servicio->iva_tipo) === '4' ? 'selected' : '' }}>4%</option>
                                <option value="10" {{ (string) old('iva_tipo', $servicio->iva_tipo) === '10' ? 'selected' : '' }}>10%</option>
                                <option value="21" {{ (string) old('iva_tipo', $servicio->iva_tipo) === '21' ? 'selected' : '' }}>21%</option>
                            </select>
                            @error('iva_tipo')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Orden --}}
                        <div>
                            <label for="orden" class="block text-sm font-medium text-slate-700">Orden</label>
                            <input type="number"
                                   id="orden"
                                   name="orden"
                                   value="{{ old('orden', $servicio->orden) }}"
                                   class="mt-1 block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('orden') border-rose-300 @enderror">
                            @error('orden')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Activo --}}
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex cursor-pointer items-center">
                                <input type="checkbox"
                                       id="activo"
                                       name="activo"
                                       value="1"
                                       {{ old('activo', $servicio->activo) ? 'checked' : '' }}
                                       class="peer sr-only">
                                <div class="h-6 w-10 rounded-full bg-slate-200 peer-focus:ring-2 peer-focus:ring-indigo-300 peer-checked:bg-indigo-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                            </label>
                            <label for="activo" class="text-sm font-medium text-slate-700">Activo</label>
                        </div>

                        <div class="flex items-center gap-3 border-t border-slate-100 pt-2">
                            <button type="submit"
                                    class="inline-flex items-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Guardar
                            </button>
                            <a href="{{ route('admin.servicios.index') }}"
                               class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>