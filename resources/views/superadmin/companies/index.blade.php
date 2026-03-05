<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Empresas</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestión de todas las empresas del sistema</p>
            </div>
            <a href="{{ route('superadmin.companies.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Empresa
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Search --}}
        <form method="GET" class="flex gap-3">
            <div class="relative flex-1 max-w-md">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Buscar empresa..."
                       class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">Buscar</button>
            @if(request('search'))
            <a href="{{ route('superadmin.companies.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">Limpiar</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Contacto</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Usuarios</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Estado</th>
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
                            {{-- Mobile card details --}}
                            <div class="mt-2 flex flex-wrap gap-1.5 sm:hidden">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $company->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $company->active ? 'Activa' : 'Inactiva' }}
                                </span>
                                @if($activePlan)
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $activePlan->name }}
                                </span>
                                @endif
                                @if($company->email)
                                <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $company->email }}
                                </span>
                                @endif
                                <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $company->users_count }} usuarios
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <div class="text-sm text-gray-600">{{ $company->email ?? '—' }}</div>
                            <div class="text-xs text-gray-400">{{ $company->phone ?? '' }}</div>
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell">
                            <span class="text-sm font-medium text-gray-700">{{ $company->users_count }}</span>
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            @if($activePlan)
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $activePlan->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">Sin plan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
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
                                      onsubmit="return confirm('¿Eliminar empresa {{ addslashes($company->name) }}?')">
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
                            No se encontraron empresas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($companies->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $companies->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
