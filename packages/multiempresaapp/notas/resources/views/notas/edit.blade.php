<x-app-layout>


    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.notas.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Editar nota</h1>
                <p class="mt-1 truncate text-sm text-slate-500">{{$nota->titulo }}</p>
            </div>
        </div>
    </x-slot>


    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

                <form method="POST" action="{{ route('admin.notas.update', $nota->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if($errors->any())
                        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">
                            <ul class="list-inside list-disc space-y-1 text-sm text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Client section --}}
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Datos del cliente</h3>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">Cliente <span class="text-rose-500">*</span></label>
                            <select name="cliente_id" required
                                    class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('cliente_id') border-rose-400 @enderror">
                                <option value="">— Selecciona un cliente —</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->id }}" {{ old('cliente_id', $nota->cliente_id) == $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}{{ $c->email ? ' — ' . $c->email : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Note section --}}
                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Nota</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Título <span class="text-rose-500">*</span></label>
                                <input type="text" name="titulo" value="{{ old('titulo', $nota->titulo) }}" required
                                       class="w-full rounded-2xl border border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('titulo') border-rose-400 @enderror">
                                @error('titulo')
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700">Contenido</label>
                                <textarea name="contenido" rows="10"
                                          class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('contenido', $nota->contenido) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-2">
                        <a href="{{ route('admin.notas.show', $nota->id) }}"
                           class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                            Cancelar
                        </a>

                        <button type="submit"
                                class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                            Guardar cambios
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>