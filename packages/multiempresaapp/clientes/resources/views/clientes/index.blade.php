<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Clientes
            </h2>
            <a href="{{ route('admin.clientes.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nuevo Cliente
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
                <form method="GET" action="{{ route('admin.clientes.index') }}" class="flex gap-2">
                    <input type="text"
                           name="buscar"
                           value="{{ $buscar ?? '' }}"
                           placeholder="Buscar por nombre, email o teléfono..."
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Buscar
                    </button>
                    @if ($buscar)
                        <a href="{{ route('admin.clientes.index') }}"
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
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Teléfono</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($clientes as $cliente)
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $cliente->nombre }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $cliente->email ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $cliente->telefono ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <a href="{{ route('admin.clientes.show', $cliente) }}"
                                       class="mr-2 text-indigo-600 hover:text-indigo-900">Ver</a>
                                    <a href="{{ route('admin.clientes.edit', $cliente) }}"
                                       class="mr-2 text-yellow-600 hover:text-yellow-900">Editar</a>
                                    <form method="POST"
                                          action="{{ route('admin.clientes.destroy', $cliente) }}"
                                          class="inline-block"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No se encontraron clientes.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile cards (bocadillos) --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($clientes as $cliente)
                    <div class="p-4 space-y-2">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $cliente->nombre }}</p>
                            @if($cliente->email)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $cliente->email }}</p>
                            @endif
                            @if($cliente->telefono)
                            <p class="text-xs text-gray-500">{{ $cliente->telefono }}</p>
                            @endif
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex flex-wrap gap-2">
                            <a href="{{ route('admin.clientes.show', $cliente) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                            <a href="{{ route('admin.clientes.edit', $cliente) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">Editar</a>
                            <form method="POST" action="{{ route('admin.clientes.destroy', $cliente) }}"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este cliente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Eliminar</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">No se encontraron clientes.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $clientes->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
