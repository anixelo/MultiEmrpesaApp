<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="flex min-w-0 flex-1 items-center gap-3">
                <a href="{{ route('admin.plantillas-presupuesto.index') }}"
                   class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                <div class="min-w-0">
                    <h1 class="truncate text-2xl font-bold text-slate-900">{{ $plantilla->nombre }}</h1>
                    <p class="mt-1 text-sm text-slate-500">Creada el {{ $plantilla->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                <form method="POST" action="{{ route('admin.plantillas-presupuesto.usar', $plantilla->id) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-1.5 rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Usar plantilla
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.plantillas-presupuesto.destroy', $plantilla->id) }}"
                      onsubmit="return confirm('¿Eliminar esta plantilla?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-1 rounded-2xl bg-rose-50 px-3 py-2.5 text-sm font-medium text-rose-700 shadow-sm transition hover:bg-rose-100">
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

            {{-- General data --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Datos generales</h3>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium text-slate-400">Empresa</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $plantilla->negocio?->name ?? '—' }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium text-slate-400">Forma de pago</dt>
                        <dd class="mt-1 text-sm font-medium text-slate-900">{{ $plantilla->forma_pago ?: '—' }}</dd>
                    </div>

                    @if($plantilla->observaciones)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-400">Observaciones para el cliente</dt>
                            <dd class="prose prose-sm mt-1 max-w-none text-slate-700">{!! $plantilla->observaciones !!}</dd>
                        </div>
                    @endif

                </dl>
            </div>

            {{-- Lines --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Líneas de presupuesto ({{ $plantilla->lineas->count() }})
                    </h3>
                </div>

                @if($plantilla->lineas->isEmpty())
                    <div class="px-6 py-8 text-center text-sm text-slate-400">
                        Esta plantilla no tiene líneas.
                    </div>
                @else
                    {{-- Desktop table --}}
                    <div class="hidden overflow-x-auto px-4 pb-4 pt-2 sm:px-6 md:block">
                        <table class="min-w-full border-separate border-spacing-y-2">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Concepto</th>
                                    <th class="px-3 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Cant.</th>
                                    <th class="px-3 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">P. Unit.</th>
                                    <th class="px-3 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Descuento</th>
                                    <th class="px-3 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">IVA %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($plantilla->lineas as $linea)
                                    <tr class="rounded-2xl bg-slate-50">
                                        <td class="rounded-l-2xl px-3 py-3 text-sm text-slate-900">{{ $linea->concepto }}</td>
                                        <td class="px-3 py-3 text-right text-sm text-slate-700">{{ number_format($linea->cantidad, 2, ',', '.') }}</td>
                                        <td class="px-3 py-3 text-right font-mono text-sm text-slate-700">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</td>
                                        <td class="px-3 py-3 text-right text-sm text-slate-500">
                                            @if($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor)
                                                {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                            @elseif($linea->descuento_tipo === 'importe' && $linea->descuento_valor)
                                                {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="rounded-r-2xl px-3 py-3 text-right text-sm text-slate-700">{{ number_format($linea->iva_tipo, 0) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="space-y-3 p-4 md:hidden">
                        @foreach($plantilla->lineas as $linea)
                            <div class="rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm font-semibold text-slate-900">{{ $linea->concepto }}</p>
                                <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                    <span>Cant: {{ number_format($linea->cantidad, 2, ',', '.') }}</span>
                                    <span>Precio: {{ number_format($linea->precio_unitario, 2, ',', '.') }} €</span>
                                    <span>IVA: {{ number_format($linea->iva_tipo, 0) }}%</span>
                                    @if($linea->descuento_tipo && $linea->descuento_valor)
                                        <span>
                                            Dto:
                                            @if($linea->descuento_tipo === 'porcentaje')
                                                {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                            @else
                                                {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
