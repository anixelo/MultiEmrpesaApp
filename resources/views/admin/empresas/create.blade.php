<x-app-layout>
 

    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.empresas.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Nueva empresa</h1>
                <p class="mt-0.5 text-sm text-slate-500">Añade una empresa para usar en tus presupuestos</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.empresas.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-rose-400 @enderror"
                           placeholder="Ej. Mi Empresa S.L.">
                    @error('name')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">NIF / CIF</label>
                        <input type="text" name="nif" value="{{ old('nif') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nif') border-rose-400 @enderror"
                               placeholder="B12345678">
                        @error('nif')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-rose-400 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Dirección</label>
                    <textarea name="address" rows="2"
                              class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address') }}</textarea>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Logo de la empresa</label>
                    <input type="file" name="logo" accept="image/*"
                           class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-2xl file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-slate-400">PNG, JPG, SVG o WebP. Máx. 2 MB.</p>
                    @error('logo')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <label class="relative inline-flex cursor-pointer items-center">
                        <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="peer sr-only">
                        <div class="h-6 w-10 rounded-full bg-slate-200 peer-focus:ring-2 peer-focus:ring-indigo-300 peer-checked:bg-indigo-600 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                    </label>
                    <span class="text-sm font-medium text-slate-700">Empresa activa</span>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-2">
                    <a href="{{ route('admin.empresas.index') }}"
                       class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                        Crear empresa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>