<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Empresas</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestiona las empresas para usar en tus presupuestos</p>
            </div>
            <a href="{{ route('admin.empresas.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Empresa
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Desktop table --}}
            <table class="hidden md:table min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">NIF/CIF</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($empresas as $empresa)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $empresa->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $empresa->nif ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $empresa->email ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $empresa->phone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $empresa->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $empresa->active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.empresas.edit', $empresa) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('admin.empresas.destroy', $empresa) }}"
                                      onsubmit="return confirm('¿Eliminar empresa {{ addslashes($empresa->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No tienes empresas. <a href="{{ route('admin.empresas.create') }}" class="text-indigo-600 hover:underline">Crear la primera</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Mobile cards --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($empresas as $empresa)
                <div class="p-4 space-y-2">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $empresa->name }}</p>
                        @if($empresa->nif)<p class="text-xs text-gray-500">NIF: {{ $empresa->nif }}</p>@endif
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $empresa->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $empresa->active ? 'Activa' : 'Inactiva' }}
                            </span>
                            @if($empresa->email)<span class="text-xs text-gray-500">{{ $empresa->email }}</span>@endif
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex flex-wrap gap-2">
                        <a href="{{ route('admin.empresas.edit', $empresa) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Editar</a>
                        <form method="POST" action="{{ route('admin.empresas.destroy', $empresa) }}"
                              onsubmit="return confirm('¿Eliminar empresa {{ addslashes($empresa->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Eliminar</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No tienes empresas.</div>
                @endforelse
            </div>

            @if($empresas->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $empresas->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
