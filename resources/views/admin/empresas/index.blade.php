<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Empresas</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Gestiona las empresas para usar en tus presupuestos
                    @if($maxEmpresas > 0)
                        &mdash; {{ $currentCount }} / {{ $maxEmpresas }} empresas activas
                    @endif
                </p>
            </div>

            <a href="{{ route('admin.empresas.create') }}"
               class="inline-flex shrink-0 items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva empresa
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            {{-- Desktop table --}}
            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Empresa</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">NIF/CIF</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Contacto</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($empresas as $empresa)
                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            @if($empresa->logo)
                                                <img src="{{ Storage::url($empresa->logo) }}" alt="" class="h-10 w-10 rounded-xl border border-slate-100 object-contain bg-white p-1">
                                            @endif
                                            <div class="font-semibold text-slate-900">{{ $empresa->name }}</div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-500">
                                        {{ $empresa->nif ?? '—' }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <div class="text-sm text-slate-600">{{ $empresa->email ?? '—' }}</div>
                                        <div class="text-xs text-slate-400">{{ $empresa->phone ?? '' }}</div>
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $empresa->active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ $empresa->active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.empresas.edit', $empresa) }}"
                                               class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar
                                            </a>

                                            <form method="POST" action="{{ route('admin.empresas.destroy', $empresa) }}"
                                                  onsubmit="return confirm('¿Eliminar empresa {{ addslashes($empresa->name) }}?')">
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
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-400">
                                        No tienes empresas. <a href="{{ route('admin.empresas.create') }}" class="text-indigo-600 hover:underline">Crear la primera</a>.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile cards --}}
            <div class="space-y-3 p-4 md:hidden">
                @forelse($empresas as $empresa)
                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            @if($empresa->logo)
                                <img src="{{ Storage::url($empresa->logo) }}" alt="" class="h-10 w-10 shrink-0 rounded-xl border border-slate-100 object-contain bg-white p-1">
                            @endif

                            <div class="min-w-0">
                                <p class="font-semibold text-slate-900">{{ $empresa->name }}</p>
                                @if($empresa->nif)
                                    <p class="text-xs text-slate-500">NIF: {{ $empresa->nif }}</p>
                                @endif

                                <div class="mt-1.5 flex flex-wrap gap-1.5">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $empresa->active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $empresa->active ? 'Activa' : 'Inactiva' }}
                                    </span>
                                    @if($empresa->email)
                                        <span class="text-xs text-slate-500">{{ $empresa->email }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('admin.empresas.edit', $empresa) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                Editar
                            </a>

                            <form method="POST" action="{{ route('admin.empresas.destroy', $empresa) }}"
                                  onsubmit="return confirm('¿Eliminar empresa {{ addslashes($empresa->name) }}?')">
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
                    <div class="px-6 py-12 text-center text-sm text-slate-400">No tienes empresas.</div>
                @endforelse
            </div>

            @if($empresas->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $empresas->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>