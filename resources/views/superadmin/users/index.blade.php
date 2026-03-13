<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Usuarios</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Gestión de todos los usuarios del sistema</p>
            </div>

            <a href="{{ route('superadmin.users.create') }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo usuario
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <form method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-center">
            <div class="relative min-w-[220px] flex-1">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input
                    name="search"
                    value="{{ request('search') }}"
                    type="text"
                    placeholder="Buscar usuario..."
                    class="w-full rounded-2xl border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >
            </div>

            <select name="role" class="rounded-2xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                <option value="">Todos los roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>

            <select name="company_id" class="rounded-2xl border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                <option value="">Todas las empresas</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit"  class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                Filtrar
            </button>

            @if(request()->hasAny(['search', 'role', 'company_id']))
                <a href="{{ route('superadmin.users.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
                    Limpiar
                </a>
            @endif
        </form>

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Listado de usuarios</h2>
                <span class="text-xs text-slate-400">{{ $users->total() }} resultado(s)</span>
            </div>

            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Usuario</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Rol</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Empresa</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">2FA</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $roleName = $user->roles->first()?->name;
                                    $roleClass = match($roleName) {
                                        'superadministrador' => 'bg-violet-100 text-violet-800',
                                        'administrador' => 'bg-blue-100 text-blue-800',
                                        default => 'bg-emerald-100 text-emerald-800',
                                    };
                                @endphp

                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" class="h-9 w-9 shrink-0 rounded-full object-cover" alt="">
                                            @else
                                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif

                                            <div class="min-w-0">
                                                <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                                <p class="truncate text-xs text-slate-400">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($roleName)
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $roleClass }}">
                                                {{ $roleName }}
                                            </span>
                                        @else
                                            <span class="text-sm text-slate-400">Sin rol</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-600">
                                        {{ $user->company?->name ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $user->two_factor_enabled ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $user->two_factor_enabled ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('superadmin.users.impersonate', $user) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100"
                                                            title="Suplantar usuario">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        Suplantar
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('superadmin.users.edit', $user) }}"
                                               class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('¿Eliminar usuario {{ addslashes($user->name) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        Eliminar
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-400">
                                        No se encontraron usuarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-3 p-4 md:hidden">
                @forelse($users as $user)
                    @php
                        $roleName = $user->roles->first()?->name;
                        $roleClass = match($roleName) {
                            'superadministrador' => 'bg-violet-100 text-violet-800',
                            'administrador' => 'bg-blue-100 text-blue-800',
                            default => 'bg-emerald-100 text-emerald-800',
                        };
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" class="h-10 w-10 shrink-0 rounded-full object-cover" alt="">
                            @else
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                <p class="truncate text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @if($roleName)
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $roleClass }}">
                                    {{ $roleName }}
                                </span>
                            @endif

                            @if($user->company)
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                                    {{ $user->company->name }}
                                </span>
                            @endif

                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $user->two_factor_enabled ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                2FA: {{ $user->two_factor_enabled ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('superadmin.users.impersonate', $user) }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                        Suplantar
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('superadmin.users.edit', $user) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                Editar
                            </a>

                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}"
                                      onsubmit="return confirm('¿Eliminar usuario {{ addslashes($user->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center text-sm text-slate-400 shadow-sm">
                        No se encontraron usuarios.
                    </div>
                @endforelse
            </div>

            @if($users->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>