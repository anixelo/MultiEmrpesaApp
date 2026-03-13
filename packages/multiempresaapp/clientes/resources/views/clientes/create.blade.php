<x-app-layout>



    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.clientes.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
 
            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Nuevo cliente</h1>
                <p class="mt-1 text-sm text-slate-500">Crea un nuevo cliente para tu cartera</p>
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST" action="{{ route('admin.clientes.store') }}" class="space-y-6">
                    @csrf

                    {{-- Nombre --}}
                    <div>
                        <label for="nombre" class="mb-1 block text-sm font-medium text-slate-700">
                            Nombre <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               required
                               class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('nombre') border-rose-300 @enderror">
                        @error('nombre')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-rose-300 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div>
                        <label for="telefono" class="mb-1 block text-sm font-medium text-slate-700">Teléfono</label>
                        <input type="text"
                               id="telefono"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('telefono') border-rose-300 @enderror">
                        @error('telefono')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notas --}}
                    <div>
                        <label for="notas" class="mb-1 block text-sm font-medium text-slate-700">Notas</label>
                        <textarea id="notas"
                                  name="notas"
                                  rows="4"
                                  class="block w-full rounded-2xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('notas') border-rose-300 @enderror">{{ old('notas') }}</textarea>
                        @error('notas')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 border-t border-slate-100 pt-2">
                        <button type="submit"
                                class="inline-flex items-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Guardar
                        </button>

                        <a href="{{ route('admin.clientes.index') }}"
                           class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>