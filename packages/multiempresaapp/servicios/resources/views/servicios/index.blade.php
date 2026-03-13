<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Conceptos</h1>
                <p class="mt-1 text-sm text-slate-500">Gestiona tus conceptos, productos y servicios</p>
            </div>

            <a href="{{ route('admin.servicios.create') }}"
               class="inline-flex shrink-0 items-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nuevo concepto
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
                <form method="GET" action="{{ route('admin.servicios.index') }}"  class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input type="text"
                           name="buscar"
                           value="{{ $buscar ?? '' }}"
                           placeholder="Buscar por nombre..."
                           class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-md">


                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Filtrar 
                    </button>

                    @if (request('buscar') )
                        <a href="{{ route('admin.servicios.index') }}"
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
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Tipo</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Precio</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">IVA</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Presup.</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($servicios as $servicio)
                                    @php
                                        $tipoColors = [
                                            'servicio' => 'bg-blue-100 text-blue-800',
                                            'producto' => 'bg-emerald-100 text-emerald-800',
                                            'otro' => 'bg-slate-100 text-slate-600',
                                        ];
                                    @endphp

                                    <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                        <td class="rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ $servicio->nombre }}
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $tipoColors[$servicio->tipo] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $servicio->tipo_label }}
                                            </span>
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ number_format($servicio->precio, 2, ',', '.') }} €
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $servicio->iva_tipo_label }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $servicio->lineas_presupuesto_count }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm">
                                            @if ($servicio->activo)
                                                <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-800">
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>

                                        <td class="rounded-r-2xl px-4 py-4 text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.servicios.show', $servicio) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Ver
                                                </a>

                                                <a href="{{ route('admin.servicios.edit', $servicio) }}"
                                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                                    Editar
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('admin.servicios.destroy', $servicio) }}"
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar este concepto?')">
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
                                        <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">
                                            No se encontraron conceptos.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="space-y-3 p-4 md:hidden">
                    @forelse ($servicios as $servicio)
                        @php
                            $tipoColors = [
                                'servicio' => 'bg-blue-100 text-blue-800',
                                'producto' => 'bg-emerald-100 text-emerald-800',
                                'otro' => 'bg-slate-100 text-slate-600',
                            ];
                        @endphp

                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $servicio->nombre }}</p>

                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $tipoColors[$servicio->tipo] ?? 'bg-slate-100 text-slate-600' }}">
                                        {{ $servicio->tipo_label }}
                                    </span>

                                    <span class="text-xs text-slate-600">{{ number_format($servicio->precio, 2, ',', '.') }} €</span>
                                    <span class="text-xs text-slate-500">IVA: {{ $servicio->iva_tipo_label }}</span>
                                    <span class="text-xs text-slate-500">{{ $servicio->lineas_presupuesto_count }} presup.</span>

                                    @if ($servicio->activo)
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800">Activo</span>
                                    @else
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium bg-slate-100 text-slate-600">Inactivo</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                                <a href="{{ route('admin.servicios.show', $servicio) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                    Ver
                                </a>

                                <a href="{{ route('admin.servicios.edit', $servicio) }}"
                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('admin.servicios.destroy', $servicio) }}"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este concepto?')">
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
                        <div class="px-6 py-10 text-center text-sm text-slate-500">No se encontraron conceptos.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $servicios->links() }}
            </div>

        </div>
    </div>
</x-app-layout>