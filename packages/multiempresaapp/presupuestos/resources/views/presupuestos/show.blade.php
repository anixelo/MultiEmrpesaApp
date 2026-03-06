<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Presupuesto {{ $presupuesto->numero }}
                </h2>
                @php
                    $colorMap = [
                        'gray'   => 'bg-gray-100 text-gray-700',
                        'blue'   => 'bg-blue-100 text-blue-700',
                        'purple' => 'bg-purple-100 text-purple-700',
                        'green'  => 'bg-green-100 text-green-700',
                        'red'    => 'bg-red-100 text-red-700',
                    ];
                    $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $badgeClass }}">
                    {{ $presupuesto->estado_label }}
                </span>
            </div>
            <a href="{{ route('admin.presupuestos.index') }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Header info --}}
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-6 py-5 grid grid-cols-2 gap-6 sm:grid-cols-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Número</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $presupuesto->numero }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $presupuesto->fecha->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Válido hasta</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $presupuesto->validez_hasta?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-500">Forma de pago</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $presupuesto->forma_pago ?? '—' }}</dd>
                    </div>
                </div>
            </div>

            {{-- Cliente --}}
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900">Cliente</h3>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $presupuesto->cliente?->nombre ?? '—' }}</p>
                    @if ($presupuesto->cliente?->email)
                        <p class="text-sm text-gray-500">{{ $presupuesto->cliente->email }}</p>
                    @endif
                    @if ($presupuesto->cliente?->telefono)
                        <p class="text-sm text-gray-500">{{ $presupuesto->cliente->telefono }}</p>
                    @endif
                </div>
            </div>

            {{-- Líneas --}}
            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-medium text-gray-900">Líneas del presupuesto</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Concepto</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Cant.</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">P. Unit.</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Descuento</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Base Imp.</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">IVA</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach ($presupuesto->lineas as $linea)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $linea->concepto }}
                                    @if ($linea->servicio)
                                        <span class="ml-1 text-xs text-gray-400">({{ $linea->servicio->nombre }})</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    {{ number_format($linea->cantidad, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    {{ number_format($linea->precio_unitario, 2, ',', '.') }} €
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    @if ($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor)
                                        {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                    @elseif ($linea->descuento_tipo === 'importe' && $linea->descuento_valor)
                                        {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    {{ number_format($linea->base_imponible, 2, ',', '.') }} €
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700">
                                    {{ number_format($linea->iva_tipo, 0) }}%
                                    ({{ number_format($linea->iva_cuota, 2, ',', '.') }} €)
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($linea->total, 2, ',', '.') }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Totales --}}
                <div class="border-t border-gray-200 px-6 py-4">
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
                        <div class="flex justify-between border-t border-gray-300 pt-2 text-base font-bold text-gray-900">
                            <span>TOTAL</span>
                            <span>{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notas y observaciones --}}
            @if ($presupuesto->notas || $presupuesto->observaciones)
                <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                    <div class="px-6 py-4 space-y-4">
                        @if ($presupuesto->notas)
                            <div>
                                <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Notas internas</h4>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $presupuesto->notas }}</p>
                            </div>
                        @endif
                        @if ($presupuesto->observaciones)
                            <div>
                                <h4 class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Observaciones para el cliente</h4>
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $presupuesto->observaciones }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Acciones --}}
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                   class="inline-flex items-center rounded-md bg-yellow-500 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-600">
                    Editar
                </a>

                @if (in_array($presupuesto->estado, ['borrador', 'visto']))
                    <form method="POST" action="{{ route('admin.presupuestos.enviar', $presupuesto->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                            Marcar como Enviado
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.presupuestos.duplicar', $presupuesto->id) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Duplicar
                    </button>
                </form>

                <a href="{{ route('presupuestos.public', $presupuesto->token_publico) }}"
                   target="_blank"
                   class="inline-flex items-center rounded-md bg-indigo-100 px-4 py-2 text-sm font-medium text-indigo-700 hover:bg-indigo-200">
                    Ver enlace público
                </a>

                <a href="{{ route('admin.presupuestos.index') }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Volver
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
