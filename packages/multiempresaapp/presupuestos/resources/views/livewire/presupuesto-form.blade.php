<div>
    @if ($presupuestoId)
        <form method="POST" action="{{ route('admin.presupuestos.update', $presupuestoId) }}">
            @csrf
            @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.presupuestos.store') }}">
            @csrf
    @endif

    {{-- Hidden field for cliente_id --}}
    <input type="hidden" name="cliente_id" value="{{ $clienteId }}">

    {{-- Hidden fields for lines (sent with form) --}}
    @foreach ($lineas as $i => $linea)
        <input type="hidden" name="lineas[{{ $i }}][concepto]"        value="{{ $linea['concepto'] }}">
        <input type="hidden" name="lineas[{{ $i }}][cantidad]"        value="{{ $linea['cantidad'] }}">
        <input type="hidden" name="lineas[{{ $i }}][precio_unitario]" value="{{ $linea['precio_unitario'] }}">
        <input type="hidden" name="lineas[{{ $i }}][iva_tipo]"        value="{{ $linea['iva_tipo'] }}">
        <input type="hidden" name="lineas[{{ $i }}][descuento_tipo]"  value="{{ $linea['descuento_tipo'] ?? '' }}">
        <input type="hidden" name="lineas[{{ $i }}][descuento_valor]" value="{{ $linea['descuento_valor'] ?? 0 }}">
        @if (!empty($linea['servicio_id']))
            <input type="hidden" name="lineas[{{ $i }}][servicio_id]" value="{{ $linea['servicio_id'] }}">
        @endif
    @endforeach

    <div class="space-y-6">

        {{-- Sección: Cliente y fechas --}}
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Datos generales</h3>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

                {{-- Cliente selector --}}
                <div class="sm:col-span-2">
                    <livewire:clientes.cliente-selector :key="'selector-' . ($presupuestoId ?? 'new')" />
                    @if ($clienteNombre)
                        <p class="mt-1 text-xs text-gray-500">Cliente seleccionado: <strong>{{ $clienteNombre }}</strong></p>
                    @endif
                    @error('cliente_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Fecha <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           wire:model.live="fecha"
                           name="fecha"
                           value="{{ $fecha }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('fecha') border-red-300 @enderror">
                    @error('fecha')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Válido hasta --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Válido hasta</label>
                    <input type="date"
                           wire:model.live="validezHasta"
                           name="validez_hasta"
                           value="{{ $validezHasta }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                {{-- Forma de pago --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Forma de pago</label>
                    <input type="text"
                           wire:model="formaPago"
                           name="forma_pago"
                           value="{{ $formaPago }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                {{-- Observaciones para el cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Observaciones (para el cliente)</label>
                    <textarea wire:model="observaciones"
                              name="observaciones"
                              rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $observaciones }}</textarea>
                </div>

                {{-- Notas internas --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Notas internas</label>
                    <textarea wire:model="notas"
                              name="notas"
                              rows="2"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ $notas }}</textarea>
                </div>

            </div>
        </div>

        {{-- Sección: Líneas --}}
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

            <div class="overflow-x-auto">
                <table class="min-w-full">
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
                                {{-- Concepto --}}
                                <td class="px-4 py-2">
                                    <input type="text"
                                           wire:model.live="lineas.{{ $i }}.concepto"
                                           placeholder="Concepto o descripción..."
                                           class="block w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('lineas.'.$i.'.concepto') border-red-300 @enderror">
                                    @error('lineas.'.$i.'.concepto')
                                        <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p>
                                    @enderror
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

        {{-- Botones de acción --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ $presupuestoId ? 'Actualizar presupuesto' : 'Crear presupuesto' }}
            </button>
            <a href="{{ route('admin.presupuestos.index') }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                Cancelar
            </a>
        </div>

    </div>
    </form>
</div>
