<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto {{ $presupuesto->numero }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">

    <div class="mx-auto max-w-4xl">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 border border-green-200 p-4">
                <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if (session('info'))
            <div class="mb-6 rounded-md bg-blue-50 border border-blue-200 p-4">
                <p class="text-sm text-blue-700 font-medium">{{ session('info') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-gray-200 flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $presupuesto->empresa?->name ?? config('app.name') }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">Presupuesto</p>
                </div>
                <div class="text-right">
                    <p class="text-xl font-semibold text-gray-900">{{ $presupuesto->numero }}</p>
                    <p class="text-sm text-gray-500">Fecha: {{ $presupuesto->fecha->format('d/m/Y') }}</p>
                    @if ($presupuesto->validez_hasta)
                        <p class="text-sm text-gray-500">Válido hasta: {{ $presupuesto->validez_hasta->format('d/m/Y') }}</p>
                    @endif
                    @php
                        $estadoStyles = [
                            'borrador'  => 'bg-gray-100 text-gray-700',
                            'enviado'   => 'bg-blue-100 text-blue-700',
                            'visto'     => 'bg-purple-100 text-purple-700',
                            'aceptado'  => 'bg-green-100 text-green-700',
                            'rechazado' => 'bg-red-100 text-red-700',
                        ];
                        $estadoLabels = [
                            'borrador'  => 'Borrador',
                            'enviado'   => 'Enviado',
                            'visto'     => 'Visto',
                            'aceptado'  => 'Aceptado',
                            'rechazado' => 'Rechazado',
                        ];
                        $badgeClass = $estadoStyles[$presupuesto->estado] ?? 'bg-gray-100 text-gray-700';
                        $estadoLabel = $estadoLabels[$presupuesto->estado] ?? ucfirst($presupuesto->estado);
                    @endphp
                    <span class="mt-2 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                        {{ $estadoLabel }}
                    </span>
                </div>
            </div>

            {{-- Cliente --}}
            <div class="px-8 py-5 border-b border-gray-200">
                <h2 class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-2">Destinatario</h2>
                <p class="font-semibold text-gray-900">{{ $presupuesto->cliente?->nombre ?? '—' }}</p>
                @if ($presupuesto->cliente?->email)
                    <p class="text-sm text-gray-600">{{ $presupuesto->cliente->email }}</p>
                @endif
                @if ($presupuesto->cliente?->telefono)
                    <p class="text-sm text-gray-600">{{ $presupuesto->cliente->telefono }}</p>
                @endif
                @if ($presupuesto->cliente?->direccion)
                    <p class="text-sm text-gray-600">{{ $presupuesto->cliente->direccion }}</p>
                @endif
            </div>

            {{-- Líneas --}}
            <div class="px-8 py-5">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-xs font-medium uppercase tracking-wider text-gray-500">
                            <th class="pb-3 text-left">Concepto</th>
                            <th class="pb-3 text-right">Cant.</th>
                            <th class="pb-3 text-right">P. Unit.</th>
                            <th class="pb-3 text-right">Descuento</th>
                            <th class="pb-3 text-right">Base Imp.</th>
                            <th class="pb-3 text-right">IVA</th>
                            <th class="pb-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($presupuesto->lineas as $linea)
                            <tr>
                                <td class="py-3 text-sm text-gray-900">{{ $linea->concepto }}</td>
                                <td class="py-3 text-right text-sm text-gray-700">{{ number_format($linea->cantidad, 2, ',', '.') }}</td>
                                <td class="py-3 text-right text-sm text-gray-700">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</td>
                                <td class="py-3 text-right text-sm text-gray-700">
                                    @if ($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor)
                                        {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                    @elseif ($linea->descuento_tipo === 'importe' && $linea->descuento_valor)
                                        {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-3 text-right text-sm text-gray-700">{{ number_format($linea->base_imponible, 2, ',', '.') }} €</td>
                                <td class="py-3 text-right text-sm text-gray-700">{{ number_format($linea->iva_tipo, 0) }}%</td>
                                <td class="py-3 text-right text-sm font-medium text-gray-900">{{ number_format($linea->total, 2, ',', '.') }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Totales --}}
            <div class="px-8 py-5 border-t border-gray-200">
                <div class="ml-auto max-w-xs space-y-1">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Subtotal bruto</span>
                        <span>{{ number_format($presupuesto->subtotal_bruto, 2, ',', '.') }} €</span>
                    </div>
                    @if ($presupuesto->subtotal_descuentos > 0)
                        <div class="flex justify-between text-sm text-red-600">
                            <span>Descuentos</span>
                            <span>- {{ number_format($presupuesto->subtotal_descuentos, 2, ',', '.') }} €</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Base imponible</span>
                        <span>{{ number_format($presupuesto->total_base_imponible, 2, ',', '.') }} €</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>IVA</span>
                        <span>{{ number_format($presupuesto->total_iva, 2, ',', '.') }} €</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-300 pt-2 text-lg font-bold text-gray-900">
                        <span>TOTAL</span>
                        <span>{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                    </div>
                </div>
            </div>

            {{-- Observaciones --}}
            @if ($presupuesto->observaciones)
                <div class="px-8 py-5 border-t border-gray-200">
                    <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-2">Observaciones</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $presupuesto->observaciones }}</p>
                </div>
            @endif

            @if ($presupuesto->forma_pago)
                <div class="px-8 py-4 border-t border-gray-200">
                    <h3 class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Forma de pago</h3>
                    <p class="text-sm text-gray-700">{{ $presupuesto->forma_pago }}</p>
                </div>
            @endif

            {{-- Accept / Reject buttons --}}
            @if ($presupuesto->estado === 'aceptado')
                <div class="px-8 py-6 border-t border-green-200 bg-green-50 text-center">
                    <p class="text-lg font-semibold text-green-700">✓ Presupuesto Aceptado</p>
                    @if ($presupuesto->aceptado_en)
                        <p class="text-sm text-green-600 mt-1">Aceptado el {{ $presupuesto->aceptado_en->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            @elseif ($presupuesto->estado === 'rechazado')
                <div class="px-8 py-6 border-t border-red-200 bg-red-50 text-center">
                    <p class="text-lg font-semibold text-red-700">✗ Presupuesto Rechazado</p>
                    @if ($presupuesto->rechazado_en)
                        <p class="text-sm text-red-600 mt-1">Rechazado el {{ $presupuesto->rechazado_en->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            @elseif (in_array($presupuesto->estado, ['enviado', 'visto']))
                <div class="px-8 py-6 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <p class="text-sm text-gray-600">¿Deseas aceptar o rechazar este presupuesto?</p>
                    <form method="POST" action="{{ route('presupuestos.aceptar', $presupuesto->token_publico) }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('¿Confirmas que deseas ACEPTAR este presupuesto?')"
                                class="inline-flex items-center rounded-md bg-green-600 px-6 py-2 text-sm font-medium text-white hover:bg-green-700">
                            ✓ Aceptar presupuesto
                        </button>
                    </form>
                    <form method="POST" action="{{ route('presupuestos.rechazar', $presupuesto->token_publico) }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('¿Confirmas que deseas RECHAZAR este presupuesto?')"
                                class="inline-flex items-center rounded-md bg-red-600 px-6 py-2 text-sm font-medium text-white hover:bg-red-700">
                            ✗ Rechazar presupuesto
                        </button>
                    </form>
                </div>
            @endif

        </div>

        <p class="mt-6 text-center text-xs text-gray-400">
            Este presupuesto ha sido generado por {{ $presupuesto->empresa?->name ?? config('app.name') }}.
        </p>
    </div>

</body>
</html>
