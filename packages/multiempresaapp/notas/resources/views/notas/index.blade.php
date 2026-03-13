<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Notas</h1>
                <p class="mt-1 text-sm text-slate-500">Gestiona tus notas y su relación con presupuestos</p>
            </div>

            <a href="{{ route('admin.notas.create') }}"
               class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nueva nota
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                    <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
                    <p class="text-sm text-rose-700">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Búsqueda --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.notas.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por título, contenido o cliente..."
                           class="block rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:min-w-[280px]">

                    <select name="presupuesto"
                            class="block rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todas las notas</option>
                        <option value="con" {{ request('presupuesto') === 'con' ? 'selected' : '' }}>Con presupuesto</option>
                        <option value="sin" {{ request('presupuesto') === 'sin' ? 'selected' : '' }}>Sin presupuesto</option>
                    </select>

                    <button type="submit"
                             class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Filtrar
                    </button>

                    @if (request('buscar') || request('presupuesto'))
                        <a href="{{ route('admin.notas.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                {{-- Desktop table --}}
                <div class="hidden md:block">
                    <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                        <table class="min-w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Título</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Cliente</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Presupuesto</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notas as $nota)
                                    <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                        <td class="rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ $nota->titulo }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ $nota->cliente?->nombre ?? '—' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm">
                                            @if($nota->presupuesto)
                                                <a href="{{ route('admin.presupuestos.show', $nota->presupuesto->id) }}"
                                                   class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 transition hover:text-indigo-900">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    {{ $nota->presupuesto->numero }}
                                                </a>
                                            @else
                                                <a href="{{ route('admin.notas.crear-presupuesto', $nota->id) }}"
                                                   class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 px-2.5 py-1.5 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Crear presupuesto
                                                </a>
                                            @endif
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $nota->created_at->format('d/m/Y') }}
                                        </td>

                                        <td class="rounded-r-2xl px-4 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.notas.show', $nota->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Ver
                                                </a>

                                                <a href="{{ route('admin.notas.edit', $nota->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                                    Editar
                                                </a>

                                                <form method="POST" action="{{ route('admin.notas.destroy', $nota->id) }}"
                                                      onsubmit="return confirm('¿Eliminar esta nota?')">
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
                                        <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                            No hay notas. <a href="{{ route('admin.notas.create') }}" class="text-indigo-600 hover:underline">Crea la primera.</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="space-y-3 p-4 md:hidden">
                    @forelse($notas as $nota)
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $nota->titulo }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $nota->cliente?->nombre ?? '—' }} · {{ $nota->created_at->format('d/m/Y') }}</p>

                                    @if($nota->presupuesto)
                                        <a href="{{ route('admin.presupuestos.show', $nota->presupuesto->id) }}"
                                           class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-indigo-600">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ $nota->presupuesto->numero }}
                                        </a>
                                    @else
                                        <a href="{{ route('admin.notas.crear-presupuesto', $nota->id) }}"
                                           class="mt-2 inline-flex items-center gap-1 rounded-xl bg-emerald-50 px-2.5 py-1.5 text-xs font-medium text-emerald-700">
                                            + Crear presupuesto
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                                <a href="{{ route('admin.notas.show', $nota->id) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                    Ver
                                </a>

                                <a href="{{ route('admin.notas.edit', $nota->id) }}"
                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('admin.notas.destroy', $nota->id) }}"
                                      onsubmit="return confirm('¿Eliminar esta nota?')">
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
                        <div class="px-6 py-12 text-center text-sm text-slate-400">
                            No hay notas. <a href="{{ route('admin.notas.create') }}" class="text-indigo-600 hover:underline">Crea la primera.</a>
                        </div>
                    @endforelse
                </div>

                @if($notas->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4">
                        {{ $notas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>