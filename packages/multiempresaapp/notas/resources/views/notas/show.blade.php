<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.notas.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $nota->titulo }}</h2>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $nota->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.notas.edit', $nota->id) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </a>
                <form method="POST" action="{{ route('admin.notas.destroy', $nota->id) }}"
                      onsubmit="return confirm('¿Eliminar esta nota?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-xl hover:bg-red-100 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
            <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
            @endif

            {{-- Client card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Cliente</h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 text-lg">{{ $nota->cliente?->nombre ?? '—' }}</p>
                        @if($nota->cliente?->email)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $nota->cliente->email }}</p>
                        @endif
                        @if($nota->cliente?->telefono)
                        <p class="text-sm text-gray-500">{{ $nota->cliente->telefono }}</p>
                        @endif
                    </div>
                    @if($nota->cliente)
                    <a href="{{ route('admin.clientes.show', $nota->cliente->id) }}"
                       class="text-xs text-indigo-600 hover:underline">Ver cliente</a>
                    @endif
                </div>
            </div>

            {{-- Presupuesto association --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Presupuesto</h3>
                </div>
                @if($nota->presupuesto)
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $nota->presupuesto->numero }}</p>
                        <p class="text-sm text-gray-500 mt-0.5">{{ $nota->presupuesto->fecha->format('d/m/Y') }}</p>
                    </div>
                    <a href="{{ route('admin.presupuestos.show', $nota->presupuesto->id) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                        Ver presupuesto
                    </a>
                </div>
                @else
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-400">Sin presupuesto asociado</p>
                    <a href="{{ route('admin.notas.crear-presupuesto', $nota->id) }}"
                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Crear presupuesto
                    </a>
                </div>
                @endif
            </div>

            {{-- Note content --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Contenido</h3>
                @if($nota->contenido)
                <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ $nota->contenido }}</div>
                @else
                <p class="text-sm text-gray-400 italic">Sin contenido</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
