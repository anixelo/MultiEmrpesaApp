<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Presupuestos
            </h2>
            <a href="{{ route('admin.presupuestos.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nuevo Presupuesto
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

            {{-- Búsqueda y filtros --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.presupuestos.index') }}" class="flex flex-wrap gap-2">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por número o cliente..."
                           class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <select name="estado"
                            class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos los estados</option>
                        <option value="borrador"  {{ request('estado') === 'borrador'  ? 'selected' : '' }}>Borrador</option>
                        <option value="enviado"   {{ request('estado') === 'enviado'   ? 'selected' : '' }}>Enviado</option>
                        <option value="visto"     {{ request('estado') === 'visto'     ? 'selected' : '' }}>Visto</option>
                        <option value="aceptado"  {{ request('estado') === 'aceptado'  ? 'selected' : '' }}>Aceptado</option>
                        <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Filtrar
                    </button>
                    @if (request('buscar') || request('estado'))
                        <a href="{{ route('admin.presupuestos.index') }}"
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
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Válido hasta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($presupuestos as $presupuesto)
                            @php
                                $colorMap = [
                                    'gray'   => 'bg-gray-100 text-gray-700',
                                    'blue'   => 'bg-blue-100 text-blue-700',
                                    'purple' => 'bg-purple-100 text-purple-700',
                                    'green'  => 'bg-green-100 text-green-700',
                                    'red'    => 'bg-red-100 text-red-700',
                                ];
                                $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $presupuesto->numero }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                    {{ $presupuesto->cliente?->nombre ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $presupuesto->fecha->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $presupuesto->validez_hasta?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $presupuesto->estado_label }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($presupuesto->total, 2, ',', '.') }} €
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                                       class="mr-2 text-indigo-600 hover:text-indigo-900">Ver</a>
                                    <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                                       class="mr-2 text-yellow-600 hover:text-yellow-900">Editar</a>
                                    <form method="POST"
                                          action="{{ route('admin.presupuestos.destroy', $presupuesto->id) }}"
                                          class="inline-block"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este presupuesto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No se encontraron presupuestos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile cards (bocadillos) --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($presupuestos as $presupuesto)
                    @php
                        $colorMap = [
                            'gray'   => 'bg-gray-100 text-gray-700',
                            'blue'   => 'bg-blue-100 text-blue-700',
                            'purple' => 'bg-purple-100 text-purple-700',
                            'green'  => 'bg-green-100 text-green-700',
                            'red'    => 'bg-red-100 text-red-700',
                        ];
                        $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $presupuesto->numero }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">{{ $presupuesto->cliente?->nombre ?? '—' }}</p>
                            </div>
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badgeClass }} shrink-0">
                                {{ $presupuesto->estado_label }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                            <span>{{ $presupuesto->fecha->format('d/m/Y') }}</span>
                            @if($presupuesto->validez_hasta)
                            <span>Válido: {{ $presupuesto->validez_hasta->format('d/m/Y') }}</span>
                            @endif
                            <span class="font-semibold text-gray-900">{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex flex-wrap gap-2">
                            <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                            <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">Editar</a>
                            <form method="POST" action="{{ route('admin.presupuestos.destroy', $presupuesto->id) }}"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este presupuesto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">No se encontraron presupuestos.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $presupuestos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
