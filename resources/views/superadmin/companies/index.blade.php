<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Cuentas</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestión de todas las cuentas del sistema</p>
            </div>
            <a href="{{ route('superadmin.companies.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Cuenta
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Search --}}
        <form method="GET" class="flex gap-3">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Buscar cuenta..."
                       class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">Buscar</button>
            @if(request('search'))
            <a href="{{ route('superadmin.companies.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">Limpiar</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Desktop table --}}
            <table class="hidden md:table min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cuenta</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuarios</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($companies as $company)
                    @php $activePlan = $company->subscription?->plan; @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $company->name }}</div>
                            <div class="text-xs text-gray-400">{{ $company->slug }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $company->email ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $company->phone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-700">{{ $company->users_count }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($activePlan)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $activePlan->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">Sin plan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $company->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $company->active ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('superadmin.companies.edit', $company) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('superadmin.companies.destroy', $company) }}"
                                      onsubmit="return confirm('¿Eliminar cuenta {{ addslashes($company->name) }}?')">
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
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">
                            No se encontraron cuentas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Mobile cards (bocadillos) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($companies as $company)
                @php $activePlan = $company->subscription?->plan; @endphp
                <div class="p-4 space-y-2">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $company->name }}</p>
                        <p class="text-xs text-gray-400">{{ $company->slug }}</p>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $company->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $company->active ? 'Activa' : 'Inactiva' }}
                            </span>
                            @if($activePlan)
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $activePlan->name }}
                            </span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $company->users_count }} usuarios</span>
                            @if($company->email)
                            <span class="text-xs text-gray-500">{{ $company->email }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex flex-wrap gap-2">
                        <a href="{{ route('superadmin.companies.edit', $company) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Editar</a>
                        <form method="POST" action="{{ route('superadmin.companies.destroy', $company) }}"
                              onsubmit="return confirm('¿Eliminar cuenta {{ addslashes($company->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">Eliminar</button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No se encontraron cuentas.</div>
                @endforelse
            </div>

            @if($companies->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $companies->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
