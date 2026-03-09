{{-- Sección: Líneas del presupuesto --}}
<div class="overflow-hidden bg-white shadow sm:rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-sm font-medium text-gray-900">Líneas del presupuesto</h3>
        <button type="button"
                wire:click="addLinea"
                class="inline-flex items-center rounded-md bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-700 hover:bg-indigo-100">
            + Añadir línea
        </button>
    </div>

    @error('lineas')
        <div class="px-6 pt-3">
            <p class="text-sm text-red-600">{{ $message }}</p>
        </div>
    @enderror

    {{-- Desktop table --}}
    <div class="hidden md:block">
        <table class="min-w-full" style="overflow: visible">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 w-1/3">Concepto</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-16">Cant.</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-24">P. Unit.</th>
                    <th class="px-4 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500 w-28">Descuento</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-20">IVA %</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-24">Base Imp.</th>
                    <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 w-24">Total</th>
                    <th class="px-4 py-3 w-10"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @foreach ($lineas as $i => $linea)
                    <tr wire:key="linea-{{ $i }}">
                        {{-- Concepto con búsqueda de conceptos --}}
                        <td class="px-4 py-2" x-data="{ open{{ $i }}: @entangle('lineaDropdownVisible.'.$i) }">
                            <div class="relative">
                                @if(!empty($linea['concepto']) && !empty($linea['servicio_id']))
                                    <div class="flex items-center gap-1 rounded border border-gray-300 bg-gray-50 px-2 py-1.5 text-sm">
                                        <span class="flex-1 truncate">{{ $linea['concepto'] }}</span>
                                        <button type="button"
                                                wire:click="$set('lineas.{{ $i }}.servicio_id', null); $set('lineas.{{ $i }}.concepto', '')"
                                                class="text-gray-400 hover:text-gray-600 text-xs leading-none">&times;</button>
                                    </div>
                                @else
                                    <input type="text"
                                           wire:model.live="lineaSearch.{{ $i }}"
                                           wire:keyup="searchServicioForLinea({{ $i }}, $event.target.value)"
                                           wire:blur="copySearchToConcepto({{ $i }})"
                                           placeholder="Buscar concepto o escribir..."
                                           autocomplete="off"
                                           class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('lineas.'.$i.'.concepto') border-red-300 @enderror">
                                    @error('lineas.'.$i.'.concepto')
                                        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                                    @enderror
                                    @if(!empty($lineaDropdownVisible[$i]))
                                        <div class="absolute z-50 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg min-w-48">
                                            @if(!empty($lineaSearchResults[$i]))
                                                <ul class="max-h-48 overflow-auto py-1">
                                                    @foreach($lineaSearchResults[$i] as $srv)
                                                        <li>
                                                            <button type="button"
                                                                    wire:click="selectServicioForLinea({{ $i }}, {{ $srv['id'] }}, {{ json_encode($srv['nombre']) }}, {{ $srv['precio'] }}, {{ $srv['iva_tipo'] ?? 'null' }})"
                                                                    class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                                                <span class="font-medium">{{ $srv['nombre'] }}</span>
                                                                <span class="ml-2 text-xs text-gray-400">{{ number_format($srv['precio'], 2, ',', '.') }} €</span>
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                            <div class="border-t border-gray-100">
                                                <button type="button"
                                                        wire:click="openServicioModal({{ $i }})"
                                                        class="w-full px-3 py-2 text-left text-sm text-indigo-600 hover:bg-indigo-50">
                                                    + Crear nuevo concepto
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </td>
                        {{-- Cantidad --}}
                        <td class="px-4 py-2">
                            <input type="number"
                                   wire:model.live="lineas.{{ $i }}.cantidad"
                                   step="0.01"
                                   min="0"
                                   class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right">
                        </td>
                        {{-- Precio unitario --}}
                        <td class="px-4 py-2">
                            <input type="number"
                                   wire:model.live="lineas.{{ $i }}.precio_unitario"
                                   step="0.01"
                                   min="0"
                                   class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right">
                        </td>
                        {{-- Descuento --}}
                        <td class="px-4 py-2">
                            <div class="flex gap-1">
                                <select wire:model.live="lineas.{{ $i }}.descuento_tipo"
                                        class="block w-16 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                                    <option value="">—</option>
                                    <option value="porcentaje">%</option>
                                    <option value="importe">€</option>
                                </select>
                                @if (!empty($linea['descuento_tipo']))
                                    <input type="number"
                                           wire:model.live="lineas.{{ $i }}.descuento_valor"
                                           step="0.01"
                                           min="0"
                                           class="block w-16 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right">
                                @endif
                            </div>
                        </td>
                        {{-- IVA tipo --}}
                        <td class="px-4 py-2">
                            <select wire:model.live="lineas.{{ $i }}.iva_tipo"
                                    class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="0">0%</option>
                                <option value="4">4%</option>
                                <option value="10">10%</option>
                                <option value="21">21%</option>
                            </select>
                        </td>
                        {{-- Base imponible (read-only) --}}
                        <td class="px-4 py-2 text-right text-sm text-gray-700">
                            {{ number_format($linea['base_imponible'] ?? 0, 2, ',', '.') }} €
                        </td>
                        {{-- Total (read-only) --}}
                        <td class="px-4 py-2 text-right text-sm font-medium text-gray-900">
                            {{ number_format($linea['total'] ?? 0, 2, ',', '.') }} €
                        </td>
                        {{-- Remove --}}
                        <td class="px-4 py-2 text-center">
                            @if (count($lineas) > 1)
                                <button type="button"
                                        wire:click="removeLinea({{ $i }})"
                                        class="text-red-400 hover:text-red-600 text-lg leading-none"
                                        title="Eliminar línea">
                                    &times;
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="md:hidden divide-y divide-gray-100">
        @foreach ($lineas as $i => $linea)
        <div class="p-4 space-y-3" wire:key="linea-card-{{ $i }}">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Línea {{ $i + 1 }}</span>
                @if (count($lineas) > 1)
                <button type="button"
                        wire:click="removeLinea({{ $i }})"
                        class="text-red-400 hover:text-red-600 text-lg leading-none"
                        title="Eliminar línea">
                    &times;
                </button>
                @endif
            </div>
            {{-- Concepto --}}
            <div x-data="{ open{{ $i }}: @entangle('lineaDropdownVisible.'.$i) }">
                <label class="block text-xs font-medium text-gray-500 mb-1">Concepto</label>
                <div class="relative">
                    @if(!empty($linea['concepto']) && !empty($linea['servicio_id']))
                        <div class="flex items-center gap-1 rounded border border-gray-300 bg-gray-50 px-2 py-1.5 text-sm">
                            <span class="flex-1 truncate">{{ $linea['concepto'] }}</span>
                            <button type="button"
                                    wire:click="$set('lineas.{{ $i }}.servicio_id', null); $set('lineas.{{ $i }}.concepto', '')"
                                    class="text-gray-400 hover:text-gray-600 text-xs leading-none">&times;</button>
                        </div>
                    @else
                        <input type="text"
                               wire:model.live="lineaSearch.{{ $i }}"
                               wire:keyup="searchServicioForLinea({{ $i }}, $event.target.value)"
                               wire:blur="copySearchToConcepto({{ $i }})"
                               placeholder="Buscar concepto o escribir..."
                               autocomplete="off"
                               class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('lineas.'.$i.'.concepto') border-red-300 @enderror">
                        @error('lineas.'.$i.'.concepto')
                            <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                        @enderror
                        @if(!empty($lineaDropdownVisible[$i]))
                            <div class="absolute z-20 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg">
                                @if(!empty($lineaSearchResults[$i]))
                                    <ul class="max-h-48 overflow-auto py-1">
                                        @foreach($lineaSearchResults[$i] as $srv)
                                            <li>
                                                <button type="button"
                                                        wire:click="selectServicioForLinea({{ $i }}, {{ $srv['id'] }}, {{ json_encode($srv['nombre']) }}, {{ $srv['precio'] }}, {{ $srv['iva_tipo'] ?? 'null' }})"
                                                        class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                                    <span class="font-medium">{{ $srv['nombre'] }}</span>
                                                    <span class="ml-2 text-xs text-gray-400">{{ number_format($srv['precio'], 2, ',', '.') }} €</span>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <div class="border-t border-gray-100">
                                    <button type="button"
                                            wire:click="openServicioModal({{ $i }})"
                                            class="w-full px-3 py-2 text-left text-sm text-indigo-600 hover:bg-indigo-50">
                                        + Crear nuevo concepto
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            {{-- Cantidad / Precio --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Cantidad</label>
                    <input type="number"
                           wire:model.live="lineas.{{ $i }}.cantidad"
                           step="0.01" min="0"
                           class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">P. Unit. (€)</label>
                    <input type="number"
                           wire:model.live="lineas.{{ $i }}.precio_unitario"
                           step="0.01" min="0"
                           class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right">
                </div>
            </div>
            {{-- Descuento / IVA --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Descuento</label>
                    <div class="flex gap-1">
                        <select wire:model.live="lineas.{{ $i }}.descuento_tipo"
                                class="block w-16 rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            <option value="">—</option>
                            <option value="porcentaje">%</option>
                            <option value="importe">€</option>
                        </select>
                        @if (!empty($linea['descuento_tipo']))
                            <input type="number"
                                   wire:model.live="lineas.{{ $i }}.descuento_valor"
                                   step="0.01" min="0"
                                   class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-right">
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">IVA %</label>
                    <select wire:model.live="lineas.{{ $i }}.iva_tipo"
                            class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="0">0%</option>
                        <option value="4">4%</option>
                        <option value="10">10%</option>
                        <option value="21">21%</option>
                    </select>
                </div>
            </div>
            {{-- Totales de la línea --}}
            <div class="flex justify-between text-sm border-t border-gray-100 pt-2">
                <span class="text-gray-500">Base imp.: <span class="text-gray-700">{{ number_format($linea['base_imponible'] ?? 0, 2, ',', '.') }} €</span></span>
                <span class="font-semibold text-gray-900">Total: {{ number_format($linea['total'] ?? 0, 2, ',', '.') }} €</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Totales --}}
    <div class="border-t border-gray-200 px-6 py-4">
        <div class="ml-auto max-w-xs space-y-1">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Subtotal bruto</span>
                <span>{{ number_format($subtotalBruto, 2, ',', '.') }} €</span>
            </div>
            @if ($subtotalDescuentos > 0)
                <div class="flex justify-between text-sm text-red-600">
                    <span>Descuentos</span>
                    <span>- {{ number_format($subtotalDescuentos, 2, ',', '.') }} €</span>
                </div>
            @endif
            <div class="flex justify-between text-sm text-gray-600">
                <span>Base imponible</span>
                <span>{{ number_format($totalBaseImponible, 2, ',', '.') }} €</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>IVA</span>
                <span>{{ number_format($totalIva, 2, ',', '.') }} €</span>
            </div>
            <div class="flex justify-between border-t border-gray-300 pt-2 text-base font-bold text-gray-900">
                <span>TOTAL</span>
                <span>{{ number_format($total, 2, ',', '.') }} €</span>
            </div>
        </div>
    </div>
</div>
