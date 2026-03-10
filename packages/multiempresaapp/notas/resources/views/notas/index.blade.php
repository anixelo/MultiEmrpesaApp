<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Notas</h2>
            </div>
            <a href="{{ route('admin.notas.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nueva Nota
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Búsqueda --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.notas.index') }}" class="flex flex-wrap gap-2">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por título, contenido o cliente..."
                           class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Filtrar
                    </button>
                    @if (request('buscar'))
                        <a href="{{ route('admin.notas.index') }}"
                           class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                {{-- Desktop table --}}
                <table class="hidden md:table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Título</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Presupuesto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($notas as $nota)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $nota->titulo }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $nota->cliente?->nombre ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($nota->presupuesto)
                                    <a href="{{ route('admin.presupuestos.show', $nota->presupuesto->id) }}"
                                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-900 font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $nota->presupuesto->numero }}
                                    </a>
                                @else
                                    <a href="{{ route('admin.notas.crear-presupuesto', $nota->id) }}"
                                       class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                        Crear presupuesto
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $nota->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.notas.show', $nota->id) }}"
                                       class="text-xs text-indigo-600 hover:text-indigo-900 font-medium">Ver</a>
                                    <a href="{{ route('admin.notas.edit', $nota->id) }}"
                                       class="text-xs text-yellow-600 hover:text-yellow-900 font-medium">Editar</a>
                                    <form method="POST" action="{{ route('admin.notas.destroy', $nota->id) }}"
                                          onsubmit="return confirm('¿Eliminar esta nota?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-medium">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                                No hay notas. <a href="{{ route('admin.notas.create') }}" class="text-indigo-600 hover:underline">Crea la primera.</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile cards --}}
                <div class="md:hidden divide-y divide-gray-200">
                    @forelse($notas as $nota)
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $nota->titulo }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $nota->cliente?->nombre ?? '—' }} · {{ $nota->created_at->format('d/m/Y') }}</p>
                                @if($nota->presupuesto)
                                    <a href="{{ route('admin.presupuestos.show', $nota->presupuesto->id) }}" class="mt-1 inline-flex items-center gap-1 text-xs text-indigo-600 font-medium">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $nota->presupuesto->numero }}
                                    </a>
                                @else
                                    <a href="{{ route('admin.notas.crear-presupuesto', $nota->id) }}" class="mt-1 inline-flex items-center gap-1 text-xs text-emerald-600 font-medium">
                                        + Crear presupuesto
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.notas.show', $nota->id) }}" class="text-xs text-indigo-600 font-medium">Ver</a>
                            <a href="{{ route('admin.notas.edit', $nota->id) }}" class="text-xs text-yellow-600 font-medium">Editar</a>
                            <form method="POST" action="{{ route('admin.notas.destroy', $nota->id) }}"
                                  onsubmit="return confirm('¿Eliminar esta nota?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 font-medium">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-12 text-center text-gray-400 text-sm">
                        No hay notas. <a href="{{ route('admin.notas.create') }}" class="text-indigo-600 hover:underline">Crea la primera.</a>
                    </div>
                    @endforelse
                </div>

                @if($notas->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $notas->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
