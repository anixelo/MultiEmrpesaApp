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

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
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

                {{-- Desktop table --}}
                <div class="hidden md:block overflow-x-auto">
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
                </div>

                {{-- Mobile cards (bocadillos) --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @foreach ($presupuesto->lineas as $linea)
                    <div class="p-4 space-y-2">
                        <p class="font-medium text-gray-900">{{ $linea->concepto }}</p>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                            <div class="text-gray-500">Cantidad</div>
                            <div class="text-right text-gray-700">{{ number_format($linea->cantidad, 2, ',', '.') }}</div>
                            <div class="text-gray-500">P. Unit.</div>
                            <div class="text-right text-gray-700">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</div>
                            @if ($linea->descuento_tipo && $linea->descuento_valor)
                            <div class="text-gray-500">Descuento</div>
                            <div class="text-right text-gray-700">
                                @if ($linea->descuento_tipo === 'porcentaje'){{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                @else{{ number_format($linea->descuento_valor, 2, ',', '.') }} €@endif
                            </div>
                            @endif
                            <div class="text-gray-500">Base Imp.</div>
                            <div class="text-right text-gray-700">{{ number_format($linea->base_imponible, 2, ',', '.') }} €</div>
                            <div class="text-gray-500">IVA</div>
                            <div class="text-right text-gray-700">{{ number_format($linea->iva_tipo, 0) }}% ({{ number_format($linea->iva_cuota, 2, ',', '.') }} €)</div>
                        </div>
                        <div class="flex justify-end border-t border-gray-100 pt-2">
                            <span class="text-sm font-semibold text-gray-900">Total: {{ number_format($linea->total, 2, ',', '.') }} €</span>
                        </div>
                    </div>
                    @endforeach
                </div>

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

                {{-- Descargar PDF --}}
                <a href="{{ route('admin.presupuestos.pdf', $presupuesto->id) }}"
                   class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar PDF
                </a>

                {{-- Enviar por WhatsApp --}}
                @if ($presupuesto->cliente?->telefono)
                @php
                    $phone = preg_replace('/[^0-9]/', '', $presupuesto->cliente->telefono);
                    if (strlen($phone) === 9) $phone = '34' . $phone;
                    $publicUrl = route('presupuestos.public', $presupuesto->token_publico);
                    $waText = urlencode("Hola {$presupuesto->cliente->nombre}, te enviamos el presupuesto {$presupuesto->numero}: {$publicUrl}");
                @endphp
                <a href="https://wa.me/{{ $phone }}?text={{ $waText }}"
                   target="_blank"
                   class="inline-flex items-center rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                    <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Enviar por WhatsApp
                </a>
                @endif

                {{-- Enviar por Email --}}
                @if ($presupuesto->cliente?->email)
                <form method="POST" action="{{ route('admin.presupuestos.send-email', $presupuesto->id) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Enviar por Email
                    </button>
                </form>
                @endif

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
