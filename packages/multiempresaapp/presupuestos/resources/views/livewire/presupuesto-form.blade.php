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

    {{-- Hidden field for negocio_id --}}
    <input type="hidden" name="negocio_id" value="{{ $negocioId }}">

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
                    <livewire:clientes.cliente-selector
                        :key="'selector-' . ($presupuestoId ?? 'new')"
                        :cliente-id="$clienteId"
                        :cliente-nombre="$clienteNombre"
                    />
                    @if ($clienteNombre)
                        <p class="mt-1 text-xs text-gray-500">Cliente seleccionado: <strong>{{ $clienteNombre }}</strong></p>
                    @endif
                    @error('cliente_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Empresa (negocio) selector --}}
                @if(count($empresasDisponibles) > 1)
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Empresa</label>
                    <select wire:model.live="negocioId"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">— Sin empresa —</option>
                        @foreach($empresasDisponibles as $emp)
                            <option value="{{ $emp['id'] }}" {{ $negocioId == $emp['id'] ? 'selected' : '' }}>{{ $emp['name'] }}</option>
                        @endforeach
                    </select>
                    @error('negocio_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @elseif(count($empresasDisponibles) === 1)
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Empresa</label>
                    <p class="mt-1 text-sm text-gray-600">{{ $empresasDisponibles[0]['name'] }}</p>
                </div>
                @endif

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
                    <select wire:model="formaPago"
                            name="forma_pago"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">— Seleccionar —</option>
                        <option value="Transferencia bancaria">Transferencia bancaria</option>
                        <option value="Bizum">Bizum</option>
                        <option value="Tarjeta de crédito o débito">Tarjeta de crédito o débito</option>
                        <option value="PayPal">PayPal</option>
                    </select>
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

            {{-- Mobile cards (bocadillos) --}}
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

    {{-- Modal crear servicio rápido --}}
    @if ($showServicioModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Nuevo Concepto</h3>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="quickServicioNombre"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('quickServicioNombre') border-red-300 @enderror">
                    @error('quickServicioNombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Precio <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="0" wire:model="quickServicioPrecio"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('quickServicioPrecio') border-red-300 @enderror">
                    @error('quickServicioPrecio') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">IVA %</label>
                    <select wire:model="quickServicioIva" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sin IVA específico</option>
                        <option value="0">0%</option>
                        <option value="4">4%</option>
                        <option value="10">10%</option>
                        <option value="21">21%</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeServicioModal"
                            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Cancelar</button>
                    <button type="button" wire:click="quickCreateServicio"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Crear</button>
                </div>
            </div>
        </div>
    @endif
</div>
