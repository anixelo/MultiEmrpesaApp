<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Clientes</h1>
                <p class="mt-1 text-sm text-slate-500">Gestiona tu cartera de clientes</p>
            </div>

            <a href="{{ route('admin.clientes.create') }}"
               class="inline-flex shrink-0 items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nuevo cliente
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
                <form method="GET" action="{{ route('admin.clientes.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input type="text"
                           name="buscar"
                           value="{{ $buscar ?? '' }}"
                           placeholder="Buscar por nombre, email o teléfono..."
                           class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:max-w-md">

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Filtrar 
                    </button>

                    @if (request('buscar') )
                        <a href="{{ route('admin.clientes.index') }}"
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
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Email</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Teléfono</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($clientes as $cliente)
                                    <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                        <td class="whitespace-nowrap rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ $cliente->nombre }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $cliente->email ?? '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $cliente->telefono ?? '—' }}
                                        </td>

                                        <td class="whitespace-nowrap rounded-r-2xl px-4 py-4 text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.clientes.show', $cliente) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Ver
                                                </a>

                                                <a href="{{ route('admin.clientes.edit', $cliente) }}"
                                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                                    Editar
                                                </a>

                                                <form method="POST"
                                                      action="{{ route('admin.clientes.destroy', $cliente) }}"
                                                      onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
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
                                        <td colspan="4" class="px-6 py-10 text-center text-sm text-slate-500">
                                            No se encontraron clientes.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="space-y-3 p-4 md:hidden">
                    @forelse ($clientes as $cliente)
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $cliente->nombre }}</p>
                                @if($cliente->email)
                                    <p class="mt-1 text-xs text-slate-500">{{ $cliente->email }}</p>
                                @endif
                                @if($cliente->telefono)
                                    <p class="text-xs text-slate-500">{{ $cliente->telefono }}</p>
                                @endif
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                                <a href="{{ route('admin.clientes.show', $cliente) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                    Ver
                                </a>

                                <a href="{{ route('admin.clientes.edit', $cliente) }}"
                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('admin.clientes.destroy', $cliente) }}"
                                      onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
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
                        <div class="px-6 py-10 text-center text-sm text-slate-500">No se encontraron clientes.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $clientes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>