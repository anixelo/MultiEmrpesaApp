<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestión de todos los usuarios del sistema</p>
            </div>
            <a href="{{ route('superadmin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nuevo Usuario
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input name="search" value="{{ request('search') }}" type="text" placeholder="Buscar usuario..."
                       class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <select name="role" class="border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Todos los roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
            <select name="company_id" class="border border-gray-300 rounded-xl text-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Todas las empresas</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-700 hover:bg-gray-50 transition">Filtrar</button>
            @if(request()->hasAny(['search','role','company_id']))
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm text-gray-500 hover:bg-gray-50 transition">Limpiar</a>
            @endif
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">Rol</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden lg:table-cell">2FA</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                <img src="{{ $user->avatar }}" class="w-8 h-8 rounded-full object-cover shrink-0" alt="">
                                @else
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    {{-- Mobile card details --}}
                                    <div class="mt-1.5 flex flex-wrap gap-1.5 sm:hidden">
                                        @if($user->roles->first())
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($user->roles->first()->name === 'superadministrador') bg-purple-100 text-purple-800
                                            @elseif($user->roles->first()->name === 'administrador') bg-blue-100 text-blue-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ $user->roles->first()->name }}
                                        </span>
                                        @endif
                                        @if($user->company)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            {{ $user->company->name }}
                                        </span>
                                        @endif
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $user->two_factor_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                            2FA: {{ $user->two_factor_enabled ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            @if($user->roles->first())
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->roles->first()->name === 'superadministrador') bg-purple-100 text-purple-800
                                @elseif($user->roles->first()->name === 'administrador') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ $user->roles->first()->name }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400">Sin rol</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 hidden md:table-cell text-sm text-gray-600">
                            {{ $user->company?->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 hidden lg:table-cell">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $user->two_factor_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $user->two_factor_enabled ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('superadmin.users.impersonate', $user) }}">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition"
                                            title="Suplantar usuario">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Suplantar
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('superadmin.users.edit', $user) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Editar
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                      onsubmit="return confirm('¿Eliminar usuario {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Eliminar
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">No se encontraron usuarios.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
