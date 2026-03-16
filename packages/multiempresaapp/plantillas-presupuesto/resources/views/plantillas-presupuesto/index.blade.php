<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Plantillas de presupuesto</h1>
                <p class="mt-1 text-sm text-slate-500">Reutiliza plantillas para crear presupuestos rápidamente</p>
            </div>
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
                <form method="GET" action="{{ route('admin.plantillas-presupuesto.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por nombre de plantilla..."
                           class="block rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:min-w-[280px]">

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Filtrar
                    </button>

                    @if (request('buscar'))
                        <a href="{{ route('admin.plantillas-presupuesto.index') }}"
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
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Nombre</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Empresa</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Forma de pago</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Líneas</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($plantillas as $plantilla)
                                    <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                        <td class="rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ $plantilla->nombre }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ $plantilla->negocio?->name ?? '—' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ $plantilla->forma_pago ?: '—' }}
                                        </td>

                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ $plantilla->lineas->count() }} línea{{ $plantilla->lineas->count() !== 1 ? 's' : '' }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $plantilla->created_at->format('d/m/Y') }}
                                        </td>

                                        <td class="rounded-r-2xl px-4 py-4 text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <form method="POST" action="{{ route('admin.plantillas-presupuesto.usar', $plantilla->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center rounded-xl bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                                                        Usar
                                                    </button>
                                                </form>

                                                <a href="{{ route('admin.plantillas-presupuesto.show', $plantilla->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Ver
                                                </a>

                                                <form method="POST" action="{{ route('admin.plantillas-presupuesto.destroy', $plantilla->id) }}"
                                                      onsubmit="return confirm('¿Eliminar esta plantilla?')">
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
                                        <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-400">
                                            No hay plantillas. Guarda un presupuesto como plantilla para empezar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="space-y-3 p-4 md:hidden">
                    @forelse($plantillas as $plantilla)
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900">{{ $plantilla->nombre }}</p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $plantilla->negocio?->name ?? '—' }} · {{ $plantilla->lineas->count() }} línea{{ $plantilla->lineas->count() !== 1 ? 's' : '' }} · {{ $plantilla->created_at->format('d/m/Y') }}
                                    </p>
                                    @if($plantilla->forma_pago)
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $plantilla->forma_pago }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                                <form method="POST" action="{{ route('admin.plantillas-presupuesto.usar', $plantilla->id) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center rounded-xl bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                                        Usar
                                    </button>
                                </form>

                                <a href="{{ route('admin.plantillas-presupuesto.show', $plantilla->id) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                    Ver
                                </a>

                                <form method="POST" action="{{ route('admin.plantillas-presupuesto.destroy', $plantilla->id) }}"
                                      onsubmit="return confirm('¿Eliminar esta plantilla?')">
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
                            No hay plantillas. Guarda un presupuesto como plantilla para empezar.
                        </div>
                    @endforelse
                </div>

                @if($plantillas->hasPages())
                    <div class="border-t border-slate-100 px-6 py-4">
                        {{ $plantillas->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
