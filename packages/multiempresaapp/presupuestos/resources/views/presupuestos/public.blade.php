<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto {{ $presupuesto->numero }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 px-4 py-6 text-slate-900 sm:py-10">

    @php
        $empresaNombre = $presupuesto->negocio?->name ?? $presupuesto->empresa?->name ?? config('app.name');

        $estadoStyles = [
            'borrador'  => 'bg-slate-100 text-slate-700 ring-1 ring-slate-200',
            'enviado'   => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
            'visto'     => 'bg-violet-50 text-violet-700 ring-1 ring-violet-200',
            'aceptado'  => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
            'rechazado' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
        ];

        $estadoLabels = [
            'borrador'  => 'Borrador',
            'enviado'   => 'Enviado',
            'visto'     => 'Visto',
            'aceptado'  => 'Aceptado',
            'rechazado' => 'Rechazado',
        ];

        $badgeClass = $estadoStyles[$presupuesto->estado] ?? 'bg-slate-100 text-slate-700 ring-1 ring-slate-200';
        $estadoLabel = $estadoLabels[$presupuesto->estado] ?? ucfirst($presupuesto->estado);
    @endphp

    <div class="mx-auto max-w-6xl">
        {{-- Top action bar --}}
        <div class="mb-4 flex justify-end">
            <a href="{{ route('presupuestos.public.pdf', $presupuesto->token_publico) }}"
               class="inline-flex items-center gap-2 rounded-2xl border border-white/60 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Descargar PDF
            </a>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 shadow-sm">
                <p class="text-sm font-medium text-emerald-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 shadow-sm">
                <p class="text-sm font-medium text-blue-700">{{ session('info') }}</p>
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-slate-200/40">
 
            
{{-- Header elegante --}}
<div class="border-b border-slate-200 bg-white px-6 py-8 sm:px-8 sm:py-10">
    <div class="flex flex-col gap-8 lg:flex-row lg:items-start lg:justify-between">
        <div class="max-w-2xl">
            @if($presupuesto->negocio?->logo)
                <div class="mb-5">
                    <img
                        src="{{ Storage::url($presupuesto->negocio->logo) }}"
                        alt="{{ $empresaNombre }}"
                        class="h-14 w-auto object-contain"
                    >
                </div>
            @endif

            <p class="text-xs font-semibold tracking-[0.22em] text-slate-400 uppercase">
                Presupuesto
            </p>

            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
                {{ $empresaNombre }}
            </h1>

            <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500 sm:text-base">
                Documento preparado para su revisión con el detalle económico y las condiciones indicadas.
            </p>
        </div>

        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-slate-50 p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                        Número
                    </p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">
                        {{ $presupuesto->numero }}
                    </p>
                </div>

                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                    {{ $estadoLabel }}
                </span>
            </div>

            <dl class="mt-4 space-y-3 text-sm">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-slate-500">Fecha</dt>
                    <dd class="font-medium text-slate-900">
                        {{ $presupuesto->fecha->format('d/m/Y') }}
                    </dd>
                </div>

                @if ($presupuesto->validez_hasta)
                    <div class="flex items-center justify-between gap-4">
                        <dt class="text-slate-500">Válido hasta</dt>
                        <dd class="font-medium text-slate-900">
                            {{ $presupuesto->validez_hasta->format('d/m/Y') }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</div>

            {{-- Datos principales --}}
            <div class="grid gap-6 border-b border-slate-100 px-6 py-6 sm:px-8 lg:grid-cols-2">
                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                    <h2 class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Emisor
                    </h2>

                    <p class="text-lg font-bold text-slate-900">{{ $empresaNombre }}</p>

                    <div class="mt-3 space-y-1 text-sm text-slate-600">
                        @if ($presupuesto->empresa?->email)
                            <p>{{ $presupuesto->empresa->email }}</p>
                        @endif
                        @if ($presupuesto->empresa?->telefono)
                            <p>{{ $presupuesto->empresa->telefono }}</p>
                        @endif
                        @if ($presupuesto->empresa?->direccion)
                            <p>{{ $presupuesto->empresa->direccion }}</p>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                    <h2 class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                        Destinatario
                    </h2>

                    <p class="text-lg font-bold text-slate-900">{{ $presupuesto->cliente?->nombre ?? '—' }}</p>

                    <div class="mt-3 space-y-1 text-sm text-slate-600">
                        @if ($presupuesto->cliente?->email)
                            <p>{{ $presupuesto->cliente->email }}</p>
                        @endif
                        @if ($presupuesto->cliente?->telefono)
                            <p>{{ $presupuesto->cliente->telefono }}</p>
                        @endif
                        @if ($presupuesto->cliente?->direccion)
                            <p>{{ $presupuesto->cliente->direccion }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Líneas --}}
            <div class="px-6 py-6 sm:px-8">
                <div class="mb-5 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Detalle del presupuesto</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Conceptos incluidos y resumen económico.
                        </p>
                    </div>
                </div>

                {{-- Desktop --}}
                <div class="hidden sm:block">
                    <div class="overflow-x-auto rounded-2xl border border-slate-200">
                        <table class="min-w-full">
                            <thead class="bg-slate-50">
                                <tr class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    <th class="px-5 py-4 text-left">Concepto</th>
                                    <th class="px-4 py-4 text-right">Cant.</th>
                                    <th class="px-4 py-4 text-right">P. unit.</th>
                                    <th class="px-4 py-4 text-right">Descuento</th>
                                    <th class="px-4 py-4 text-right">Base imp.</th>
                                    <th class="px-4 py-4 text-right">IVA</th>
                                    <th class="px-5 py-4 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @foreach ($presupuesto->lineas as $linea)
                                    <tr class="hover:bg-slate-50/70">
                                        <td class="px-5 py-4 align-top">
                                            <p class="text-sm font-semibold text-slate-900">{{ $linea->concepto }}</p>
                                            @if (!empty($linea->descripcion))
                                                <p class="mt-1 text-xs text-slate-500">{{ $linea->descripcion }}</p>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm text-slate-700">
                                            {{ number_format($linea->cantidad, 2, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm text-slate-700">
                                            {{ number_format($linea->precio_unitario, 2, ',', '.') }} €
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm text-slate-700">
                                            @if ($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor)
                                                {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                            @elseif ($linea->descuento_tipo === 'importe' && $linea->descuento_valor)
                                                {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm text-slate-700">
                                            {{ number_format($linea->base_imponible, 2, ',', '.') }} €
                                        </td>
                                        <td class="px-4 py-4 text-right text-sm text-slate-700">
                                            {{ number_format($linea->iva_tipo, 0) }}%
                                        </td>
                                        <td class="px-5 py-4 text-right text-sm font-semibold text-slate-900">
                                            {{ number_format($linea->total, 2, ',', '.') }} €
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile --}}
                <div class="space-y-4 sm:hidden">
                    @foreach ($presupuesto->lineas as $linea)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                            <div class="border-b border-slate-100 px-4 py-4">
                                <p class="font-semibold text-slate-900">{{ $linea->concepto }}</p>
                                @if (!empty($linea->descripcion))
                                    <p class="mt-1 text-sm text-slate-500">{{ $linea->descripcion }}</p>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-x-4 gap-y-3 px-4 py-4 text-sm">
                                <div class="text-slate-500">Cantidad</div>
                                <div class="text-right font-medium text-slate-700">
                                    {{ number_format($linea->cantidad, 2, ',', '.') }}
                                </div>

                                <div class="text-slate-500">P. unit.</div>
                                <div class="text-right font-medium text-slate-700">
                                    {{ number_format($linea->precio_unitario, 2, ',', '.') }} €
                                </div>

                                <div class="text-slate-500">Descuento</div>
                                <div class="text-right font-medium text-slate-700">
                                    @if ($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor)
                                        {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                    @elseif ($linea->descuento_tipo === 'importe' && $linea->descuento_valor)
                                        {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                    @else
                                        —
                                    @endif
                                </div>

                                <div class="text-slate-500">Base imp.</div>
                                <div class="text-right font-medium text-slate-700">
                                    {{ number_format($linea->base_imponible, 2, ',', '.') }} €
                                </div>

                                <div class="text-slate-500">IVA</div>
                                <div class="text-right font-medium text-slate-700">
                                    {{ number_format($linea->iva_tipo, 0) }}%
                                </div>
                            </div>

                            <div class="border-t border-slate-100 bg-slate-50 px-4 py-3 text-right">
                                <span class="text-sm font-bold text-slate-900">
                                    Total: {{ number_format($linea->total, 2, ',', '.') }} €
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Totales --}}
            <div class="border-t border-slate-100 bg-slate-50/70 px-6 py-6 sm:px-8">
                <div class="grid gap-6 lg:grid-cols-[1fr_360px] lg:items-start">
                    <div class="hidden lg:block"></div>

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 px-5 py-4">
                            <h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Resumen
                            </h3>
                        </div>

                        <div class="space-y-3 px-5 py-5">
                            <div class="flex justify-between text-sm text-slate-600">
                                <span>Subtotal bruto</span>
                                <span>{{ number_format($presupuesto->subtotal_bruto, 2, ',', '.') }} €</span>
                            </div>

                            @if ($presupuesto->subtotal_descuentos > 0)
                                <div class="flex justify-between text-sm font-medium text-rose-600">
                                    <span>Descuentos</span>
                                    <span>- {{ number_format($presupuesto->subtotal_descuentos, 2, ',', '.') }} €</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm text-slate-600">
                                <span>Base imponible</span>
                                <span>{{ number_format($presupuesto->total_base_imponible, 2, ',', '.') }} €</span>
                            </div>

                            <div class="flex justify-between text-sm text-slate-600">
                                <span>IVA</span>
                                <span>{{ number_format($presupuesto->total_iva, 2, ',', '.') }} €</span>
                            </div>

                            <div class="border-t border-slate-200 pt-4">
                                <div class="flex items-center justify-between rounded-2xl bg-slate-900 px-4 py-4 text-white shadow-lg">
                                    <span class="text-sm font-semibold uppercase tracking-wider">Total</span>
                                    <span class="text-xl font-bold">{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Observaciones / forma de pago --}}
            @if ($presupuesto->observaciones || $presupuesto->forma_pago)
                <div class="grid gap-6 border-t border-slate-100 px-6 py-6 sm:px-8 lg:grid-cols-2">
                    @if ($presupuesto->observaciones)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h3 class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Observaciones
                            </h3>
                            <p class="prose prose-sm max-w-none text-sm leading-6 text-slate-700">{!! $presupuesto->observaciones !!}</p>
                        </div>
                    @endif

                    @if ($presupuesto->forma_pago)
                        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                            <h3 class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                Forma de pago
                            </h3>
                            <p class="text-sm leading-6 text-slate-700">{{ $presupuesto->forma_pago }}</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Estado final / acciones --}}
            @if ($presupuesto->estado === 'aceptado')
                <div class="border-t border-emerald-200 bg-emerald-50 px-6 py-8 text-center sm:px-8">
                    <div class="mx-auto max-w-xl">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-700">
                            ✓
                        </div>
                        <p class="text-xl font-bold text-emerald-700">Presupuesto aceptado</p>
                        @if ($presupuesto->aceptado_en)
                            <p class="mt-2 text-sm text-emerald-600">
                                Aceptado el {{ $presupuesto->aceptado_en->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @elseif ($presupuesto->estado === 'rechazado')
                <div class="border-t border-rose-200 bg-rose-50 px-6 py-8 text-center sm:px-8">
                    <div class="mx-auto max-w-xl">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 text-2xl text-rose-700">
                            ✕
                        </div>
                        <p class="text-xl font-bold text-rose-700">Presupuesto rechazado</p>
                        @if ($presupuesto->rechazado_en)
                            <p class="mt-2 text-sm text-rose-600">
                                Rechazado el {{ $presupuesto->rechazado_en->format('d/m/Y H:i') }}
                            </p>
                        @endif
                    </div>
                </div>
            @elseif (in_array($presupuesto->estado, ['enviado', 'visto']))
                <div class="border-t border-slate-100 bg-white px-6 py-8 sm:px-8">
                    <div class="mx-auto max-w-3xl rounded-3xl border border-slate-200 bg-slate-50 px-6 py-6 text-center shadow-sm">
                        <h3 class="text-xl font-bold text-slate-900">¿Deseas aceptar o rechazar este presupuesto?</h3>
                        <p class="mt-2 text-sm text-slate-600">
                            Revisa la información y confirma tu decisión.
                        </p>

                        <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                            <form method="POST" action="{{ route('presupuestos.aceptar', $presupuesto->token_publico) }}">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('¿Confirmas que deseas ACEPTAR este presupuesto?')"
                                    class="inline-flex min-w-[220px] items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-100 transition hover:bg-emerald-700"
                                >
                                    ✓ Aceptar presupuesto
                                </button>
                            </form>

                            <form method="POST" action="{{ route('presupuestos.rechazar', $presupuesto->token_publico) }}">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('¿Confirmas que deseas RECHAZAR este presupuesto?')"
                                    class="inline-flex min-w-[220px] items-center justify-center rounded-2xl bg-rose-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-rose-100 transition hover:bg-rose-700"
                                >
                                    ✕ Rechazar presupuesto
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            Este presupuesto ha sido generado por {{ $empresaNombre }}.
        </p>
    </div>

</body>
</html>