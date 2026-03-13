<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="flex min-w-0 flex-1 items-center gap-3">
            <a href="{{ route('admin.notas.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

                <div class="min-w-0">
                    <h1 class="truncate text-2xl font-bold text-slate-900">{{ $nota->titulo }}</h1>
                    <p class="mt-1 text-sm text-slate-500">{{ $nota->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                <a href="{{ route('admin.notas.edit', $nota->id) }}"
                   class="inline-flex items-center gap-1 rounded-2xl bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 shadow-sm transition hover:bg-indigo-100">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>

                <form method="POST" action="{{ route('admin.notas.destroy', $nota->id) }}"
                      onsubmit="return confirm('¿Eliminar esta nota?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1 rounded-2xl bg-rose-50 px-3 py-2 text-sm font-medium text-rose-700 shadow-sm transition hover:bg-rose-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Client card --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Cliente</h3>

                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold text-slate-900">{{ $nota->cliente?->nombre ?? '—' }}</p>
                        @if($nota->cliente?->email)
                            <p class="mt-0.5 text-sm text-slate-500">{{ $nota->cliente->email }}</p>
                        @endif
                        @if($nota->cliente?->telefono)
                            <p class="text-sm text-slate-500">{{ $nota->cliente->telefono }}</p>
                        @endif
                    </div>

                    @if($nota->cliente)
                        <a href="{{ route('admin.clientes.show', $nota->cliente->id) }}"
                           class="text-xs font-medium text-indigo-600 transition hover:underline">
                            Ver cliente
                        </a>
                    @endif
                </div>
            </div>

            {{-- Presupuesto association --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Presupuesto</h3>
                </div>

                @if($nota->presupuesto)
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $nota->presupuesto->numero }}</p>
                            <p class="mt-0.5 text-sm text-slate-500">{{ $nota->presupuesto->fecha->format('d/m/Y') }}</p>
                        </div>

                        <a href="{{ route('admin.presupuestos.show', $nota->presupuesto->id) }}"
                           class="inline-flex items-center gap-1 rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                            Ver presupuesto
                        </a>
                    </div>
                @else
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm text-slate-400">Sin presupuesto asociado</p>

                        <a href="{{ route('admin.notas.crear-presupuesto', $nota->id) }}"
                           class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 px-3 py-2 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Crear presupuesto
                        </a>
                    </div>
                @endif
            </div>

            {{-- Note content --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Contenido</h3>

                @if($nota->contenido)
                    <div class="prose prose-sm max-w-none text-slate-700">{!! $nota->contenido !!}</div>
                @else
                    <p class="text-sm italic text-slate-400">Sin contenido</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>