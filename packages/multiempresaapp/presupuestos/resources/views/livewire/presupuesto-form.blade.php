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

    @if (!$presupuestoId)
    {{-- ============================
         CREATE MODE: 4-Step Wizard
         ============================ --}}

    {{-- Step indicator --}}
    <div class="mb-6">
        <ol class="flex items-center w-full text-sm font-medium text-center text-gray-500">
            @php
                $steps = [
                    1 => 'Empresa',
                    2 => 'Cliente',
                    3 => 'Datos generales',
                    4 => 'Líneas',
                ];
            @endphp
            @foreach($steps as $num => $label)
            <li class="flex {{ $num < count($steps) ? 'md:w-full' : '' }} items-center {{ $step >= $num ? 'text-indigo-600' : 'text-gray-400' }}">
                <span class="flex items-center gap-1.5 {{ $num < count($steps) ? 'after:content-[\'/\'] md:after:content-[\'\'] after:mx-2 md:after:w-full md:after:h-px md:after:border md:after:border-gray-200 md:after:inline-block md:after:mx-4' : '' }}">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold
                        {{ $step > $num ? 'bg-indigo-600 text-white' : ($step === $num ? 'border-2 border-indigo-600 text-indigo-600' : 'border-2 border-gray-300 text-gray-400') }}">
                        @if($step > $num)
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            {{ $num }}
                        @endif
                    </span>
                    <span class="hidden sm:inline">{{ $label }}</span>
                </span>
            </li>
            @endforeach
        </ol>
    </div>

    {{-- Step 1: Datos de la empresa --}}
    @if($step === 1)
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Paso 1 — Datos de la empresa</h3>
            <p class="text-xs text-gray-500 mt-0.5">Selecciona la empresa con la que emitirás el presupuesto</p>
        </div>
        <div class="px-6 py-5">
            @if(count($empresasDisponibles) > 1)
            <div>
                <label class="block text-sm font-medium text-gray-700">Empresa <span class="text-red-500">*</span></label>
                <select wire:model.live="negocioId"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">— Seleccionar empresa —</option>
                    @foreach($empresasDisponibles as $emp)
                        <option value="{{ $emp['id'] }}" {{ $negocioId == $emp['id'] ? 'selected' : '' }}>{{ $emp['name'] }}</option>
                    @endforeach
                </select>
            </div>
            @elseif(count($empresasDisponibles) === 1)
            <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                <p class="text-sm font-medium text-indigo-800">{{ $empresasDisponibles[0]['name'] }}</p>
                <p class="text-xs text-indigo-600 mt-0.5">Empresa seleccionada automáticamente</p>
            </div>
            @else
            <div class="p-4 bg-amber-50 rounded-lg border border-amber-200">
                <p class="text-sm text-amber-800">No tienes empresas configuradas. Puedes continuar sin seleccionar empresa o <a href="{{ route('admin.empresas.create') }}" class="font-medium underline">crear una</a>.</p>
            </div>
            @endif
        </div>
        <div class="px-6 pb-5 flex justify-end">
            <button type="button" wire:click="nextStep"
                    class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Siguiente
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 2: Datos del cliente --}}
    @if($step === 2)
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Paso 2 — Datos del cliente</h3>
            <p class="text-xs text-gray-500 mt-0.5">Busca o crea el cliente para este presupuesto</p>
        </div>
        <div class="px-6 py-5">
            <livewire:clientes.cliente-selector
                :key="'selector-new'"
                :cliente-id="$clienteId"
                :cliente-nombre="$clienteNombre"
            />
            @if ($clienteNombre)
                <p class="mt-2 text-xs text-gray-500">Cliente seleccionado: <strong>{{ $clienteNombre }}</strong></p>
            @endif
            @error('clienteId')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="px-6 pb-5 flex justify-between">
            <button type="button" wire:click="prevStep"
                    class="inline-flex items-center gap-2 rounded-md bg-gray-100 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Anterior
            </button>
            <button type="button" wire:click="nextStep"
                    class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Siguiente
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 3: Datos generales --}}
    @if($step === 3)
    <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-sm font-medium text-gray-900">Paso 3 — Datos generales</h3>
            <p class="text-xs text-gray-500 mt-0.5">Completa la información básica del presupuesto</p>
        </div>
        <div class="px-6 py-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

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
        <div class="px-6 pb-5 flex justify-between">
            <button type="button" wire:click="prevStep"
                    class="inline-flex items-center gap-2 rounded-md bg-gray-100 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Anterior
            </button>
            <button type="button" wire:click="nextStep"
                    class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-5 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Siguiente
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>
    @endif

    {{-- Step 4: Líneas --}}
    @if($step === 4)
    @include('presupuestos::livewire.partials.lineas-section')
    <div class="mt-4 flex items-center gap-3">
        <button type="button" wire:click="prevStep"
                class="inline-flex items-center gap-2 rounded-md bg-gray-100 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Anterior
        </button>
        <button type="submit"
                class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Crear presupuesto
        </button>
        <a href="{{ route('admin.presupuestos.index') }}"
           class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
            Cancelar
        </a>
    </div>
    @endif

    @else
    {{-- ============================
         EDIT MODE: 4 Sections
         ============================ --}}
    <div class="space-y-6">

        {{-- Sección 1: Datos de la empresa --}}
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Datos de la empresa</h3>
            </div>
            <div class="px-6 py-5">
                @if(count($empresasDisponibles) > 1)
                <div>
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
                <div>
                    <label class="block text-sm font-medium text-gray-700">Empresa</label>
                    <p class="mt-1 text-sm text-gray-600">{{ $empresasDisponibles[0]['name'] }}</p>
                </div>
                @else
                <p class="text-sm text-gray-500 italic">Sin empresa asignada.</p>
                @endif
            </div>
        </div>

        {{-- Sección 2: Datos del cliente --}}
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Datos del cliente</h3>
            </div>
            <div class="px-6 py-5">
                <livewire:clientes.cliente-selector
                    :key="'selector-' . $presupuestoId"
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
        </div>

        {{-- Sección 3: Datos generales --}}
        <div class="overflow-hidden bg-white shadow sm:rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-sm font-medium text-gray-900">Datos generales</h3>
            </div>
            <div class="px-6 py-5 grid grid-cols-1 gap-5 sm:grid-cols-2">

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

        {{-- Sección 4: Líneas --}}
        @include('presupuestos::livewire.partials.lineas-section')

        {{-- Botones de acción --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Actualizar presupuesto
            </button>
            <a href="{{ route('admin.presupuestos.index') }}"
               class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                Cancelar
            </a>
        </div>

    </div>
    @endif

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
