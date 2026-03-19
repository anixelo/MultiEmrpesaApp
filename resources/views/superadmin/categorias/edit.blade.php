<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('superadmin.categorias.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Editar categoría</h1>
                <p class="mt-1 truncate text-sm text-slate-500">{{ $categoria->titulo }}</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('superadmin.categorias.update', $categoria) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    @include('superadmin.categorias._form')
                </div>

                <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('superadmin.categorias.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
