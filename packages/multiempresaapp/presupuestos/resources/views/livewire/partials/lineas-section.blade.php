{{-- Sección: Líneas del presupuesto --}}
<div class="rounded-3xl border border-slate-200 bg-white shadow-sm overflow-visible">
    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
        <h3 class="text-sm font-semibold text-slate-900">Líneas del presupuesto</h3>

        <button type="button"
                wire:click="addLinea"
                class="inline-flex items-center rounded-2xl bg-indigo-50 px-3 py-2 text-sm font-medium text-indigo-700 transition hover:bg-indigo-100">
            + Añadir línea
        </button>
    </div>

    @error('lineas')
        <div class="px-6 pt-3">
            <p class="text-sm text-rose-600">{{ $message }}</p>
        </div>
    @enderror

    {{-- Desktop --}}
    <div class="hidden md:block">
        <div class="overflow-x-auto overflow-y-visible px-4 pb-4 pt-2 sm:px-6">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="w-1/3 px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Concepto</th>
                        <th class="w-16 px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Cant.</th>
                        <th class="w-24 px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">P. Unit.</th>
                        <th class="w-28 px-4 py-2 text-center text-[11px] font-semibold tracking-[0.08em] text-slate-400">Descuento</th>
                        <th class="w-20 px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">IVA %</th>
                        <th class="w-24 px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Base imp.</th>
                        <th class="w-24 px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Total</th>
                        <th class="w-10 px-4 py-2"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lineas as $i => $linea)
                        <tr wire:key="linea-{{ $i }}"
                            class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">

                            {{-- Concepto --}}
                            <td class="rounded-l-2xl px-4 py-4">
                                <div
                                    x-data="{
                                        open: @entangle('lineaDropdownVisible.' . $i),
                                        top: 0,
                                        left: 0,
                                        width: 0,
                                        updatePosition() {
                                            const el = this.$refs.input;
                                            if (!el) return;
                                            const r = el.getBoundingClientRect();
                                            this.top = r.bottom + 8;
                                            this.left = r.left;
                                            this.width = r.width;
                                        }
                                    }"
                                    x-init="
                                        $watch('open', value => {
                                            if (value) updatePosition();
                                        });
                                        window.addEventListener('resize', () => { if (open) updatePosition() });
                                        window.addEventListener('scroll', () => { if (open) updatePosition() }, true);
                                    "
                                    class="relative"
                                >
                                    @if(!empty($linea['concepto']) && !empty($linea['servicio_id']))
                                        <div class="flex items-center gap-1 rounded-2xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                            <span class="flex-1 truncate">{{ $linea['concepto'] }}</span>
                                            <button
                                                type="button"
                                                wire:click="$set('lineas.{{ $i }}.servicio_id', null); $set('lineas.{{ $i }}.concepto', '')"
                                                class="text-xs leading-none text-slate-400 transition hover:text-slate-600">
                                                &times;
                                            </button>
                                        </div>
                                    @else
                                        <div class="flex gap-2">
                                            <input
                                                x-ref="input"
                                                type="text"
                                                wire:model.live="lineaSearch.{{ $i }}"
                                                wire:keyup="searchServicioForLinea({{ $i }}, $event.target.value)"
                                                wire:focus="$set('lineaDropdownVisible.{{ $i }}', true)"
                                                wire:blur="copySearchToConcepto({{ $i }})"
                                                @focus="updatePosition()"
                                                @input="updatePosition()"
                                                placeholder="Buscar concepto o escribir..."
                                                autocomplete="off"
                                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('lineas.'.$i.'.concepto') border-rose-300 @enderror"
                                            >

                                            <button
                                                type="button"
                                                wire:click="openServicioSearchModal({{ $i }})"
                                                class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-2.5 py-2 text-slate-500 transition hover:bg-slate-50 hover:text-indigo-600"
                                                title="Buscar en lista">
                                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                                </svg>
                                            </button>
                                        </div>

                                        @error('lineas.'.$i.'.concepto')
                                            <p class="mt-0.5 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror

                                        <template x-teleport="body">
                                            <div
                                                x-show="open"
                                                x-transition
                                                :style="`position: fixed; top: ${top}px; left: ${left}px; width: ${width}px; z-index: 9999;`"
                                                class="rounded-2xl border border-slate-200 bg-white shadow-xl"
                                            >
                                                @if(!empty($lineaSearchResults[$i]))
                                                    <ul class="max-h-48 overflow-auto py-1">
                                                        @foreach($lineaSearchResults[$i] as $srv)
                                                            <li>
                                                                <button
                                                                    type="button"
                                                                    wire:click="selectServicioForLinea({{ $i }}, {{ $srv['id'] }}, {{ json_encode($srv['nombre']) }}, {{ $srv['precio'] }}, {{ $srv['iva_tipo'] ?? 'null' }})"
                                                                    class="w-full px-3 py-2 text-left text-sm text-slate-700 transition hover:bg-indigo-50 hover:text-indigo-700"
                                                                >
                                                                    <span class="font-medium">{{ $srv['nombre'] }}</span>
                                                                    <span class="ml-2 text-xs text-slate-400">{{ number_format($srv['precio'], 2, ',', '.') }} €</span>
                                                                </button>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif

                                                <div class="border-t border-slate-100">
                                                    <button
                                                        type="button"
                                                        wire:click="openServicioModal({{ $i }})"
                                                        class="w-full px-3 py-2 text-left text-sm text-indigo-600 transition hover:bg-indigo-50">
                                                        + Crear nuevo concepto
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    @endif
                                </div>
                            </td>

                            {{-- Cantidad --}}
                            <td class="px-4 py-4">
                                <input type="number"
                                       wire:model.live="lineas.{{ $i }}.cantidad"
                                       step="0.01"
                                       min="0"
                                       class="block w-full rounded-2xl border border-slate-300 bg-white px-3 py-2 text-right text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            </td>

                            {{-- Precio unitario --}}
                            <td class="px-4 py-4">
                                <input type="number"
                                       wire:model.live="lineas.{{ $i }}.precio_unitario"
                                       step="0.01"
                                       min="0"
                                       class="block w-full rounded-2xl border border-slate-300 bg-white px-3 py-2 text-right text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            </td>

                            {{-- Descuento --}}
                            <td class="px-4 py-4">
                                <div class="flex gap-2">
                                    <select wire:model.live="lineas.{{ $i }}.descuento_tipo"
                                            class="block w-16 rounded-2xl border border-slate-300 bg-white px-2 py-2 text-xs text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                        <option value="">—</option>
                                        <option value="porcentaje">%</option>
                                        <option value="importe">€</option>
                                    </select>

                                    @if (!empty($linea['descuento_tipo']))
                                        <input type="number"
                                               wire:model.live="lineas.{{ $i }}.descuento_valor"
                                               step="0.01"
                                               min="0"
                                               class="block w-16 rounded-2xl border border-slate-300 bg-white px-2 py-2 text-right text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                    @endif
                                </div>
                            </td>

                            {{-- IVA --}}
                            <td class="px-4 py-4">
                                <select wire:model.live="lineas.{{ $i }}.iva_tipo"
                                        class="block w-full rounded-2xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                    <option value="0">0%</option>
                                    <option value="4">4%</option>
                                    <option value="10">10%</option>
                                    <option value="21">21%</option>
                                </select>
                            </td>

                            {{-- Base imponible --}}
                            <td class="px-4 py-4 text-right text-sm text-slate-700">
                                {{ number_format($linea['base_imponible'] ?? 0, 2, ',', '.') }} €
                            </td>

                            {{-- Total --}}
                            <td class="px-4 py-4 text-right text-sm font-semibold text-slate-900">
                                {{ number_format($linea['total'] ?? 0, 2, ',', '.') }} €
                            </td>

                            {{-- Eliminar --}}
                            <td class="rounded-r-2xl px-4 py-4 text-center">
                                @if (count($lineas) > 1)
                                    <button type="button"
                                            wire:click="removeLinea({{ $i }})"
                                            class="text-lg leading-none text-rose-400 transition hover:text-rose-600"
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
    </div>

    {{-- Mobile --}}
    <div class="space-y-3 p-4 md:hidden">
        @foreach ($lineas as $i => $linea)
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm" wire:key="linea-card-{{ $i }}">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Línea {{ $i + 1 }}</span>
                    @if (count($lineas) > 1)
                        <button type="button"
                                wire:click="removeLinea({{ $i }})"
                                class="text-lg leading-none text-rose-400 transition hover:text-rose-600"
                                title="Eliminar línea">
                            &times;
                        </button>
                    @endif
                </div>

                {{-- Concepto --}}
                <div class="mt-3" x-data="{ open: @entangle('lineaDropdownVisible.' . $i) }">
                    <label class="mb-1 block text-xs font-medium text-slate-500">Concepto</label>

                    <div class="relative">
                        @if(!empty($linea['concepto']) && !empty($linea['servicio_id']))
                            <div class="flex items-center gap-1 rounded-2xl border border-slate-300 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                <span class="flex-1 truncate">{{ $linea['concepto'] }}</span>
                                <button type="button"
                                        wire:click="$set('lineas.{{ $i }}.servicio_id', null); $set('lineas.{{ $i }}.concepto', '')"
                                        class="text-xs leading-none text-slate-400 transition hover:text-slate-600">
                                    &times;
                                </button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input type="text"
                                       wire:model.live="lineaSearch.{{ $i }}"
                                       wire:keyup="searchServicioForLinea({{ $i }}, $event.target.value)"
                                       wire:focus="$set('lineaDropdownVisible.{{ $i }}', true)"
                                       wire:blur="copySearchToConcepto({{ $i }})"
                                       placeholder="Buscar concepto o escribir..."
                                       autocomplete="off"
                                       class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('lineas.'.$i.'.concepto') border-rose-300 @enderror">

                                <button type="button"
                                        wire:click="openServicioSearchModal({{ $i }})"
                                        class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-2.5 py-2 text-slate-500 transition hover:bg-slate-50 hover:text-indigo-600"
                                        title="Buscar en lista">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </button>
                            </div>

                            @error('lineas.'.$i.'.concepto')
                                <p class="mt-0.5 text-xs text-rose-600">{{ $message }}</p>
                            @enderror

                            @if(!empty($lineaDropdownVisible[$i]))
                                <div class="absolute z-20 mt-2 w-full rounded-2xl border border-slate-200 bg-white shadow-xl">
                                    @if(!empty($lineaSearchResults[$i]))
                                        <ul class="max-h-48 overflow-auto py-1">
                                            @foreach($lineaSearchResults[$i] as $srv)
                                                <li>
                                                    <button type="button"
                                                            wire:click="selectServicioForLinea({{ $i }}, {{ $srv['id'] }}, {{ json_encode($srv['nombre']) }}, {{ $srv['precio'] }}, {{ $srv['iva_tipo'] ?? 'null' }})"
                                                            class="w-full px-3 py-2 text-left text-sm text-slate-700 transition hover:bg-indigo-50 hover:text-indigo-700">
                                                        <span class="font-medium">{{ $srv['nombre'] }}</span>
                                                        <span class="ml-2 text-xs text-slate-400">{{ number_format($srv['precio'], 2, ',', '.') }} €</span>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="border-t border-slate-100">
                                        <button type="button"
                                                wire:click="openServicioModal({{ $i }})"
                                                class="w-full px-3 py-2 text-left text-sm text-indigo-600 transition hover:bg-indigo-50">
                                            + Crear nuevo concepto
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Cantidad / Precio --}}
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Cantidad</label>
                        <input type="number"
                               wire:model.live="lineas.{{ $i }}.cantidad"
                               step="0.01"
                               min="0"
                               class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-right text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">P. unit. (€)</label>
                        <input type="number"
                               wire:model.live="lineas.{{ $i }}.precio_unitario"
                               step="0.01"
                               min="0"
                               class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-right text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>
                </div>

                {{-- Descuento / IVA --}}
                <div class="mt-3 grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">Descuento</label>
                        <div class="flex gap-2">
                            <select wire:model.live="lineas.{{ $i }}.descuento_tipo"
                                    class="block w-16 rounded-2xl border border-slate-300 bg-white px-2 py-2 text-xs text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <option value="">—</option>
                                <option value="porcentaje">%</option>
                                <option value="importe">€</option>
                            </select>

                            @if (!empty($linea['descuento_tipo']))
                                <input type="number"
                                       wire:model.live="lineas.{{ $i }}.descuento_valor"
                                       step="0.01"
                                       min="0"
                                       class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-right text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-medium text-slate-500">IVA %</label>
                        <select wire:model.live="lineas.{{ $i }}.iva_tipo"
                                class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="0">0%</option>
                            <option value="4">4%</option>
                            <option value="10">10%</option>
                            <option value="21">21%</option>
                        </select>
                    </div>
                </div>

                {{-- Totales línea --}}
                <div class="mt-3 flex justify-between border-t border-slate-100 pt-3 text-sm">
                    <span class="text-slate-500">
                        Base imp.:
                        <span class="text-slate-700">{{ number_format($linea['base_imponible'] ?? 0, 2, ',', '.') }} €</span>
                    </span>

                    <span class="font-semibold text-slate-900">
                        Total: {{ number_format($linea['total'] ?? 0, 2, ',', '.') }} €
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Totales --}}
    <div class="border-t border-slate-100 px-6 py-5">
        <div class="ml-auto max-w-xs space-y-1">
            <div class="flex justify-between text-sm text-slate-600">
                <span>Subtotal bruto</span>
                <span>{{ number_format($subtotalBruto, 2, ',', '.') }} €</span>
            </div>

            @if ($subtotalDescuentos > 0)
                <div class="flex justify-between text-sm text-rose-600">
                    <span>Descuentos</span>
                    <span>- {{ number_format($subtotalDescuentos, 2, ',', '.') }} €</span>
                </div>
            @endif

            <div class="flex justify-between text-sm text-slate-600">
                <span>Base imponible</span>
                <span>{{ number_format($totalBaseImponible, 2, ',', '.') }} €</span>
            </div>

            <div class="flex justify-between text-sm text-slate-600">
                <span>IVA</span>
                <span>{{ number_format($totalIva, 2, ',', '.') }} €</span>
            </div>

            <div class="flex justify-between border-t border-slate-300 pt-2 text-base font-bold text-slate-900">
                <span>TOTAL</span>
                <span>{{ number_format($total, 2, ',', '.') }} €</span>
            </div>
        </div>
    </div>
</div>