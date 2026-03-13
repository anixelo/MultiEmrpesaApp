<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Usuarios</h1>
                <p class="mt-0.5 text-sm text-slate-500">{{ $company->name }} &mdash; {{ $currentCount }} / {{ $maxUsers }} usuarios</p>
            </div>

            @if($company->canAddUser())
                <a href="{{ route('admin.users.create') }}"
                   class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo usuario
                </a>
            @else
                <span class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-400 cursor-not-allowed"
                      title="Has alcanzado el límite de usuarios de tu plan">
                    Límite alcanzado
                </span>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 py-8 px-4 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {{-- Desktop table --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Usuario</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Email</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Rol</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-xs font-bold text-indigo-700">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-900">{{ $user->name }}</span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-500">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-4 py-4">
                                        @if($role = $user->roles->first())
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $role->name === 'administrador' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $role->name }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                                      onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}?')">
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
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-slate-400">
                                        No hay usuarios en esta empresa.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile cards --}}
            <div class="space-y-3 p-4 md:hidden">
                @forelse($users as $user)
                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-700">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-slate-900">{{ $user->name }}</p>
                                <p class="mt-0.5 break-all text-xs text-slate-500">{{ $user->email }}</p>

                                @if($role = $user->roles->first())
                                    <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $role->name === 'administrador' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $role->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                Editar
                            </a>

                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('¿Eliminar a {{ addslashes($user->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                        Eliminar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-sm text-slate-400">
                        No hay usuarios en esta empresa.
                    </div>
                @endforelse
            </div>

            @if($users->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>