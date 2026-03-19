<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Categorías</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Gestiona las categorías de noticias</p>
            </div>

            <a href="{{ route('superadmin.categorias.create') }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva categoría
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Listado de categorías</h2>
                <span class="text-xs text-slate-400">{{ $categorias->total() }} resultado(s)</span>
            </div>

            {{-- Desktop table --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Categoría</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Noticias</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">URL pública</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($categorias as $categoria)
                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($categoria->imagen)
                                                <img
                                                    src="{{ Storage::url($categoria->imagen) }}"
                                                    alt="{{ $categoria->titulo }}"
                                                    class="h-10 w-14 shrink-0 rounded-xl object-cover"
                                                >
                                            @else
                                                <div class="flex h-10 w-14 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <p class="line-clamp-1 text-sm font-semibold text-slate-900">{{ $categoria->titulo }}</p>
                                                <p class="mt-1 text-xs text-slate-400">{{ $categoria->slug }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                                            {{ $categoria->noticias_count }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-500">
                                        <a href="{{ route('noticias.categoria', $categoria->slug) }}"
                                           target="_blank"
                                           class="text-indigo-600 hover:underline">
                                            /{{ $categoria->slug }}
                                        </a>
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            <a href="{{ route('superadmin.categorias.edit', $categoria) }}"
                                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('superadmin.categorias.destroy', $categoria) }}"
                                                  onsubmit="return confirm('¿Eliminar esta categoría? Las noticias asociadas quedarán sin categoría.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center text-sm text-slate-400">
                                        No hay categorías aún.
                                        <a href="{{ route('superadmin.categorias.create') }}" class="font-medium text-indigo-600 hover:underline">Crea la primera</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile cards --}}
            <div class="space-y-3 p-4 md:hidden">
                @forelse($categorias as $categoria)
                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            @if($categoria->imagen)
                                <img
                                    src="{{ Storage::url($categoria->imagen) }}"
                                    alt="{{ $categoria->titulo }}"
                                    class="h-10 w-14 shrink-0 rounded-xl object-cover"
                                >
                            @else
                                <div class="flex h-10 w-14 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $categoria->titulo }}</p>
                                <div class="mt-1 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                        {{ $categoria->noticias_count }} noticias
                                    </span>
                                    <a href="{{ route('noticias.categoria', $categoria->slug) }}"
                                       target="_blank"
                                       class="text-xs text-indigo-600 hover:underline">
                                        /{{ $categoria->slug }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('superadmin.categorias.edit', $categoria) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('superadmin.categorias.destroy', $categoria) }}"
                                  onsubmit="return confirm('¿Eliminar esta categoría?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center text-sm text-slate-400 shadow-sm">
                        No hay categorías aún.
                        <a href="{{ route('superadmin.categorias.create') }}" class="font-medium text-indigo-600 hover:underline">Crea la primera</a>.
                    </div>
                @endforelse
            </div>

            @if($categorias->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $categorias->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
