<div>
    @if($plantillaSuccess)
        <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
            {{ $plantillaSuccess }}
            <a href="{{ route('admin.plantillas-presupuesto.index') }}" class="ml-2 font-medium underline hover:no-underline">Ver plantillas</a>
        </div>
    @endif

    @if ($presupuestoId)
        <form method="POST" action="{{ route('admin.presupuestos.update', $presupuestoId) }}">
            @csrf
            @method('PUT')
    @else
        <form method="POST" action="{{ route('admin.presupuestos.store') }}">
            @csrf
    @endif

    <input type="hidden" name="cliente_id" value="{{ $clienteId }}">
    <input type="hidden" name="negocio_id" value="{{ $negocioId }}">
    @if($notaId)
        <input type="hidden" name="nota_id" value="{{ $notaId }}">
    @endif
    @if($plantillaIdAplicada)
        <input type="hidden" name="plantilla_id" value="{{ $plantillaIdAplicada }}">
    @endif

    <input type="hidden" name="fecha" value="{{ $fecha }}">
    <input type="hidden" name="validez_hasta" value="{{ $validezHasta }}">
    <input type="hidden" name="forma_pago" value="{{ $formaPago }}">
    <input type="hidden" name="observaciones" value="{{ $observaciones }}">
    <input type="hidden" name="notas" value="{{ $notas }}">

    @foreach ($lineas as $i => $linea)
        <input type="hidden" name="lineas[{{ $i }}][concepto]" value="{{ $linea['concepto'] }}">
        <input type="hidden" name="lineas[{{ $i }}][cantidad]" value="{{ $linea['cantidad'] }}">
        <input type="hidden" name="lineas[{{ $i }}][precio_unitario]" value="{{ $linea['precio_unitario'] }}">
        <input type="hidden" name="lineas[{{ $i }}][iva_tipo]" value="{{ $linea['iva_tipo'] }}">
        <input type="hidden" name="lineas[{{ $i }}][descuento_tipo]" value="{{ $linea['descuento_tipo'] ?? '' }}">
        <input type="hidden" name="lineas[{{ $i }}][descuento_valor]" value="{{ $linea['descuento_valor'] ?? 0 }}">
        @if (!empty($linea['servicio_id']))
            <input type="hidden" name="lineas[{{ $i }}][servicio_id]" value="{{ $linea['servicio_id'] }}">
        @endif
    @endforeach

@if (!$presupuestoId)

    <div class="mb-8">
        @php
            $steps = [
                1 => 'Empresa',
                2 => 'Cliente',
                3 => ['desktop' => 'Datos generales', 'mobile' => 'Datos'],
                4 => 'Líneas',
            ];
        @endphp

        <ol class="grid grid-cols-4 gap-2 sm:grid-cols-2 sm:gap-4 xl:grid-cols-4">
            @foreach($steps as $num => $label)
                @php
                    $completed = $step > $num;
                    $current = $step === $num;
                    $upcoming = $step < $num;
                @endphp

                <li class="relative">
                    <div class="rounded-3xl border p-3 shadow-sm transition-all sm:flex sm:items-center sm:gap-4 sm:p-4
                        {{ $completed ? 'border-indigo-200 bg-indigo-50' : '' }}
                        {{ $current ? 'border-indigo-500 bg-white ring-2 ring-indigo-100 shadow-md' : '' }}
                        {{ $upcoming ? 'border-slate-200 bg-slate-50' : '' }}">

                        @if($num < count($steps))
                            <div class="absolute top-1/2 -right-2 z-0 hidden h-px w-4 bg-slate-200 xl:block"></div>
                        @endif

                        {{-- móvil: número arriba y textos debajo --}}
                        <div class="flex flex-col items-center text-center sm:hidden">
                            <div class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold
                                {{ $completed ? 'bg-indigo-600 text-white' : '' }}
                                {{ $current ? 'border-2 border-indigo-600 bg-white text-indigo-600' : '' }}
                                {{ $upcoming ? 'border-2 border-slate-300 bg-white text-slate-400' : '' }}">
                                @if($completed)
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    {{ $num }}
                                @endif
                            </div>

                            <div class="mt-2 min-w-0">
                                <p class="text-[11px] font-semibold uppercase tracking-wide
                                    {{ $completed ? 'text-indigo-600' : '' }}
                                    {{ $current ? 'text-indigo-600' : '' }}
                                    {{ $upcoming ? 'text-slate-400' : '' }}">
                                    Paso {{ $num }}
                                </p>

                                <p class="mt-0.5 text-xs font-semibold leading-tight
                                    {{ $completed ? 'text-slate-900' : '' }}
                                    {{ $current ? 'text-slate-900' : '' }}
                                    {{ $upcoming ? 'text-slate-500' : '' }}">
    <span class="sm:hidden">{{ $label['mobile'] ?? $label }}</span>
    <span class="hidden sm:inline">{{ $label['desktop'] ?? $label }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- ordenador: exactamente como estaba --}}
                        <div class="relative z-10 hidden h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold sm:flex
                            {{ $completed ? 'bg-indigo-600 text-white' : '' }}
                            {{ $current ? 'border-2 border-indigo-600 bg-white text-indigo-600' : '' }}
                            {{ $upcoming ? 'border-2 border-slate-300 bg-white text-slate-400' : '' }}">
                            @if($completed)
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            @else
                                {{ $num }}
                            @endif
                        </div>

                        <div class="hidden min-w-0 sm:block">
                            <p class="text-xs font-semibold uppercase tracking-wide
                                {{ $completed ? 'text-indigo-600' : '' }}
                                {{ $current ? 'text-indigo-600' : '' }}
                                {{ $upcoming ? 'text-slate-400' : '' }}">
                                Paso {{ $num }}
                            </p>

                            <p class="text-sm font-semibold
                                {{ $completed ? 'text-slate-900' : '' }}
                                {{ $current ? 'text-slate-900' : '' }}
                                {{ $upcoming ? 'text-slate-500' : '' }}">
    <span class="sm:hidden">{{ $label['mobile'] ?? $label }}</span>
    <span class="hidden sm:inline">{{ $label['desktop'] ?? $label }}</span>
                            </p>
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>

 


        @if($step === 1)
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Paso 1 — Datos de la empresa</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Selecciona la empresa con la que emitirás el presupuesto</p>
                </div>

                <div class="px-6 py-5">
                    @if(count($empresasDisponibles) > 1)
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Empresa <span class="text-rose-500">*</span></label>
                            <select wire:model.live="negocioId"
                                    class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <option value="">— Seleccionar empresa —</option>
                                @foreach($empresasDisponibles as $emp)
                                    <option value="{{ $emp['id'] }}" {{ $negocioId == $emp['id'] ? 'selected' : '' }}>{{ $emp['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif(count($empresasDisponibles) === 1)
                        <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                            <p class="text-sm font-medium text-indigo-800">{{ $empresasDisponibles[0]['name'] }}</p>
                            <p class="mt-0.5 text-xs text-indigo-600">Empresa seleccionada automáticamente</p>
                        </div>
                    @else
                        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-sm text-amber-800">No tienes empresas configuradas. Puedes continuar sin seleccionar empresa o <a href="{{ route('admin.empresas.create') }}" class="font-medium underline">crear una</a>.</p>
                        </div>
                    @endif
                </div>

                <div class="flex justify-end px-6 pb-5">
                    @if($canUsePlantillas)
                    <button type="button" wire:click="openSelectPlantillaModal"
                            class="mr-auto inline-flex items-center gap-2 rounded-2xl border border-violet-300 bg-violet-50 px-4 py-2.5 text-sm font-medium text-violet-700 shadow-sm transition hover:bg-violet-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Usar plantilla
                    </button>
                    @endif
                    <button type="button" wire:click="nextStep"
                            class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Siguiente
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if($step === 2)
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Paso 2 — Datos del cliente</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Busca o crea el cliente para este presupuesto</p>
                </div>

                <div class="px-6 py-5">
                    <livewire:clientes.cliente-selector
                        :key="'selector-new'"
                        :cliente-id="$clienteId"
                        :cliente-nombre="$clienteNombre"
                    />

                    @if ($clienteNombre)
                        <p class="mt-2 text-xs text-slate-500">Cliente seleccionado: <strong>{{ $clienteNombre }}</strong></p>
                    @endif

                    @error('clienteId')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-between px-6 pb-5">
                    <button type="button" wire:click="prevStep"
                            class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>

                    <button type="button" wire:click="nextStep"
                            class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Siguiente
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if($step === 3)
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Paso 3 — Datos generales</h3>
                    <p class="mt-0.5 text-xs text-slate-500">Completa la información básica del presupuesto</p>
                </div>

                <div class="grid grid-cols-1 gap-5 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Fecha <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               wire:model.live="fecha"
                               name="fecha"
                               value="{{ $fecha }}"
                               class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('fecha') border-rose-300 @enderror">
                        @error('fecha')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Válido hasta</label>
                        <input type="date"
                               wire:model.live="validezHasta"
                               name="validez_hasta"
                               value="{{ $validezHasta }}"
                               class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Forma de pago</label>
                        <select wire:model="formaPago"
                                name="forma_pago"
                                class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">— Seleccionar —</option>
                            <option value="Transferencia bancaria">Transferencia bancaria</option>
                            <option value="Bizum">Bizum</option>
                            <option value="Tarjeta de crédito o débito">Tarjeta de crédito o débito</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Notas internas</label>
                        <textarea wire:model="notas"
                                  name="notas"
                                  rows="2"
                                  class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">{{ $notas }}</textarea>
                    </div>

                    <div class="sm:col-span-2"
                         x-data="presupuestoObsEditor(@js($observaciones))"
                         x-init="init()"
                         wire:ignore>
                        <label class="block text-sm font-medium text-slate-700">Observaciones (para el cliente)</label>

                        <input type="hidden" name="observaciones" x-model="content" id="observaciones-hidden-create">

                        {{-- Toolbar --}}
                        <div class="mt-1 flex flex-wrap items-center gap-0.5 rounded-t-2xl border border-slate-300 bg-slate-50 px-2 py-1.5">
                            <button type="button" @click="exec('bold')" title="Negrita"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-sm font-bold text-slate-700 transition hover:bg-slate-200"><b>B</b></button>
                            <button type="button" @click="exec('italic')" title="Cursiva"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-sm italic text-slate-700 transition hover:bg-slate-200"><i>I</i></button>
                            <button type="button" @click="exec('underline')" title="Subrayado"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-sm underline text-slate-700 transition hover:bg-slate-200"><u>U</u></button>
                            <div class="mx-1 h-5 w-px bg-slate-300"></div>
                            <button type="button" @click="exec('insertUnorderedList')" title="Lista"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-slate-700 transition hover:bg-slate-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                            </button>
                        </div>

                        {{-- Editor area --}}
                        <div x-ref="editor"
                             contenteditable="true"
                             @input="onInput()"
                             @blur="syncToWire()"
                             class="min-h-[4rem] rounded-b-2xl border border-t-0 border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100 [&_ul]:list-disc [&_ul]:pl-4 [&_ol]:list-decimal [&_ol]:pl-4"></div>
                    </div>
                </div>

                <div class="flex justify-between px-6 pb-5">
                    <button type="button" wire:click="prevStep"
                            class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Anterior
                    </button>

                    <button type="button" wire:click="nextStep"
                            class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Siguiente
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        @if($step === 4)
            @if($notaId && $notaTitulo)
                <div class="mb-4 overflow-hidden rounded-2xl border border-amber-200 bg-amber-50">
                    <div class="flex items-center justify-between border-b border-amber-200 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="text-sm font-semibold text-amber-800">Nota: {{ $notaTitulo }}</span>
                        </div>

                        <button type="button" wire:click="toggleNotaPanel"
                                class="text-xs font-medium text-amber-700 underline transition hover:text-amber-900">
                            {{ $showNotaPanel ? 'Ocultar' : 'Mostrar' }}
                        </button>
                    </div>

                    @if($showNotaPanel && $notaContenido)
                        <div class="px-4 py-3">
                            <p class="whitespace-pre-wrap text-sm text-amber-900">{{ $notaContenido }}</p>
                        </div>
                    @endif
                </div>
            @endif

            @include('presupuestos::livewire.partials.lineas-section')

            <div class="mt-4 flex flex-wrap items-center gap-3">
                <button type="button" wire:click="prevStep"
                        class="inline-flex items-center gap-2 rounded-2xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Anterior
                </button>

                <button type="submit"
                        class="inline-flex items-center rounded-2xl bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Crear presupuesto
                </button>

                @if($canUsePlantillas)
                <button type="button" wire:click="openPlantillaModal"
                        class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Guardar como plantilla
                </button>
                @endif

                <a href="{{ route('admin.presupuestos.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Cancelar
                </a>
            </div>
        @endif

    @else
        <div class="space-y-6">
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Datos de la empresa</h3>
                </div>

                <div class="px-6 py-5">
                    @if(count($empresasDisponibles) > 1)
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Empresa</label>
                            <select wire:model.live="negocioId"
                                    class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                                <option value="">— Sin empresa —</option>
                                @foreach($empresasDisponibles as $emp)
                                    <option value="{{ $emp['id'] }}" {{ $negocioId == $emp['id'] ? 'selected' : '' }}>{{ $emp['name'] }}</option>
                                @endforeach
                            </select>
                            @error('negocio_id')
                                <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @elseif(count($empresasDisponibles) === 1)
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Empresa</label>
                            <p class="mt-1 text-sm text-slate-600">{{ $empresasDisponibles[0]['name'] }}</p>
                        </div>
                    @else
                        <p class="text-sm italic text-slate-500">Sin empresa asignada.</p>
                    @endif
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Datos del cliente</h3>
                </div>

                <div class="px-6 py-5">
                    <livewire:clientes.cliente-selector
                        :key="'selector-' . $presupuestoId"
                        :cliente-id="$clienteId"
                        :cliente-nombre="$clienteNombre"
                    />

                    @if ($clienteNombre)
                        <p class="mt-1 text-xs text-slate-500">Cliente seleccionado: <strong>{{ $clienteNombre }}</strong></p>
                    @endif

                    @error('cliente_id')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Datos generales</h3>
                </div>

                <div class="grid grid-cols-1 gap-5 px-6 py-5 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">
                            Fecha <span class="text-rose-500">*</span>
                        </label>
                        <input type="date"
                               wire:model.live="fecha"
                               name="fecha"
                               value="{{ $fecha }}"
                               class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('fecha') border-rose-300 @enderror">
                        @error('fecha')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Válido hasta</label>
                        <input type="date"
                               wire:model.live="validezHasta"
                               name="validez_hasta"
                               value="{{ $validezHasta }}"
                               class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700">Forma de pago</label>
                        <select wire:model="formaPago"
                                name="forma_pago"
                                class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                            <option value="">— Seleccionar —</option>
                            <option value="Transferencia bancaria">Transferencia bancaria</option>
                            <option value="Bizum">Bizum</option>
                            <option value="Tarjeta de crédito o débito">Tarjeta de crédito o débito</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-slate-700">Notas internas</label>
                        <textarea wire:model="notas"
                                  name="notas"
                                  rows="2"
                                  class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">{{ $notas }}</textarea>
                    </div>

                    <div class="sm:col-span-2"
                         x-data="presupuestoObsEditor(@js($observaciones))"
                         x-init="init()"
                         wire:ignore>
                        <label class="block text-sm font-medium text-slate-700">Observaciones (para el cliente)</label>

                        <input type="hidden" name="observaciones" x-model="content" id="observaciones-hidden-edit">

                        {{-- Toolbar --}}
                        <div class="mt-1 flex flex-wrap items-center gap-0.5 rounded-t-2xl border border-slate-300 bg-slate-50 px-2 py-1.5">
                            <button type="button" @click="exec('bold')" title="Negrita"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-sm font-bold text-slate-700 transition hover:bg-slate-200"><b>B</b></button>
                            <button type="button" @click="exec('italic')" title="Cursiva"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-sm italic text-slate-700 transition hover:bg-slate-200"><i>I</i></button>
                            <button type="button" @click="exec('underline')" title="Subrayado"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-sm underline text-slate-700 transition hover:bg-slate-200"><u>U</u></button>
                            <div class="mx-1 h-5 w-px bg-slate-300"></div>
                            <button type="button" @click="exec('insertUnorderedList')" title="Lista"
                                    class="flex h-7 w-7 items-center justify-center rounded p-1 text-slate-700 transition hover:bg-slate-200">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                            </button>
                        </div>

                        {{-- Editor area --}}
                        <div x-ref="editor"
                             contenteditable="true"
                             @input="onInput()"
                             @blur="syncToWire()"
                             class="min-h-[4rem] rounded-b-2xl border border-t-0 border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100 [&_ul]:list-disc [&_ul]:pl-4 [&_ol]:list-decimal [&_ol]:pl-4"></div>
                    </div>
                </div>
            </div>

            @if($notaId && $notaTitulo)
                <div class="overflow-hidden rounded-2xl border border-amber-200 bg-amber-50">
                    <div class="flex items-center justify-between border-b border-amber-200 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="text-sm font-semibold text-amber-800">Nota: {{ $notaTitulo }}</span>
                        </div>

                        <button type="button" wire:click="toggleNotaPanel"
                                class="text-xs font-medium text-amber-700 underline transition hover:text-amber-900">
                            {{ $showNotaPanel ? 'Ocultar' : 'Mostrar' }}
                        </button>
                    </div>

                    @if($showNotaPanel && $notaContenido)
                        <div class="px-4 py-3">
                            <div class="prose prose-sm max-w-none text-amber-900">{!! $notaContenido !!}</div>
                        </div>
                    @endif
                </div>
            @endif

            @include('presupuestos::livewire.partials.lineas-section')

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center rounded-2xl bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Actualizar presupuesto
                </button>

                @if($canUsePlantillas)
                <button type="button" wire:click="openPlantillaModal"
                        class="inline-flex items-center gap-1.5 rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Guardar como plantilla
                </button>
                @endif

                <a href="{{ route('admin.presupuestos.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Cancelar
                </a>
            </div>
        </div>
    @endif

    </form>

    @if ($showServicioSearchModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Buscar concepto</h3>
                    <button type="button" wire:click="closeServicioSearchModal" class="text-slate-400 transition hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4">
                    <input type="text"
                           wire:model.live="servicioSearchModalQuery"
                           placeholder="Buscar concepto por nombre..."
                           autofocus
                           class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>

                <div class="max-h-72 overflow-y-auto border-t border-slate-100">
                    @if (count($servicioSearchModalResults) > 0)
                        <ul class="divide-y divide-slate-100">
                            @foreach ($servicioSearchModalResults as $srv)
                                <li>
                                    <button type="button"
                                            wire:click="selectServicioFromModal({{ $servicioSearchModalLineaIndex }}, {{ $srv['id'] }}, {{ json_encode($srv['nombre']) }}, {{ $srv['precio'] }}, {{ $srv['iva_tipo'] ?? 'null' }})"
                                            class="w-full px-6 py-3 text-left transition hover:bg-indigo-50">
                                        <p class="text-sm font-medium text-slate-900">{{ $srv['nombre'] }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">{{ number_format($srv['precio'], 2, ',', '.') }} € · IVA {{ $srv['iva_tipo'] !== null ? $srv['iva_tipo'] . '%' : '—' }}</p>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @elseif(strlen($servicioSearchModalQuery) >= 1)
                        <div class="px-6 py-4 text-sm text-slate-500">No se encontraron conceptos con "{{ $servicioSearchModalQuery }}".</div>
                    @else
                        <div class="px-6 py-4 text-sm text-slate-400">Escribe para buscar conceptos...</div>
                    @endif
                </div>

                <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
                    <button type="button"
                            wire:click="openCreateFromServicioSearch"
                            class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 transition hover:text-indigo-700">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Crear concepto
                    </button>

                    <button type="button"
                            wire:click="closeServicioSearchModal"
                            class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($showServicioModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-6 shadow-2xl">
                <h3 class="mb-4 text-lg font-semibold text-slate-900">Nuevo concepto</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="quickServicioNombre"
                           class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('quickServicioNombre') border-rose-300 @enderror">
                    @error('quickServicioNombre') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700">Precio <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" min="0" wire:model="quickServicioPrecio"
                           class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('quickServicioPrecio') border-rose-300 @enderror">
                    @error('quickServicioPrecio') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700">IVA %</label>
                    <select wire:model="quickServicioIva"
                            class="mt-1 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Sin IVA específico</option>
                        <option value="0">0%</option>
                        <option value="4">4%</option>
                        <option value="10">10%</option>
                        <option value="21">21%</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="closeServicioModal"
                            class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        Cancelar
                    </button>

                    <button type="button" wire:click="quickCreateServicio"
                            class="inline-flex items-center rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                        Crear
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Plantilla modal --}}
    @if($showPlantillaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
            <div class="w-full max-w-md rounded-3xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Guardar como plantilla</h3>
                    <button type="button" wire:click="closePlantillaModal" class="text-slate-400 transition hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5">
                    <label class="block text-sm font-medium text-slate-700">
                        Nombre de la plantilla <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="plantillaNombre"
                           wire:keydown.enter.prevent="saveAsPlantilla"
                           placeholder="Ej: Plantilla mantenimiento mensual"
                           class="mt-1.5 block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                    @error('plantillaNombre')
                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 border-t border-slate-100 px-6 py-4">
                    <button type="button" wire:click="closePlantillaModal"
                            class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        Cancelar
                    </button>
                    <button type="button" wire:click="saveAsPlantilla"
                            class="inline-flex items-center rounded-2xl bg-indigo-600 px-5 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">
                        Guardar plantilla
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Select-plantilla modal (step 1) --}}
    @if($showSelectPlantillaModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
             wire:keydown.escape="closeSelectPlantillaModal">
            <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-lg font-semibold text-slate-900">Seleccionar plantilla</h3>
                    <button type="button" wire:click="closeSelectPlantillaModal" class="text-slate-400 transition hover:text-slate-600">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4">
                    <input type="text"
                           wire:model.live="selectPlantillaQuery"
                           placeholder="Filtrar por nombre..."
                           autofocus
                           class="block w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                </div>

                <div class="max-h-80 overflow-y-auto border-t border-slate-100">
                    @if(count($selectPlantillaResults) > 0)
                        <ul class="divide-y divide-slate-100">
                            @foreach($selectPlantillaResults as $pt)
                                <li>
                                    <button type="button"
                                            wire:click="applyPlantillaFromModal({{ $pt['id'] }})"
                                            class="w-full px-6 py-3 text-left transition hover:bg-violet-50">
                                        <p class="text-sm font-medium text-slate-900">{{ $pt['nombre'] }}</p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ $pt['lineas'] }} línea{{ $pt['lineas'] !== 1 ? 's' : '' }}
                                            @if($pt['forma_pago']) · {{ $pt['forma_pago'] }} @endif
                                        </p>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="px-6 py-8 text-center text-sm text-slate-400">
                            @if($selectPlantillaQuery !== '')
                                No se encontraron plantillas con "{{ $selectPlantillaQuery }}".
                            @else
                                No hay plantillas disponibles.
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex justify-end border-t border-slate-100 px-6 py-4">
                    <button type="button" wire:click="closeSelectPlantillaModal"
                            class="inline-flex items-center rounded-2xl bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-200">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function presupuestoObsEditor(initial) {
    return {
        content: initial || '',
        _timer: null,
        init() {
            this.$refs.editor.innerHTML = this.content;
        },
        onInput() {
            this.content = this.$refs.editor.innerHTML;
            clearTimeout(this._timer);
            this._timer = setTimeout(() => {
                this.$wire.set('observaciones', this.content);
            }, 400);
        },
        syncToWire() {
            clearTimeout(this._timer);
            this.$wire.set('observaciones', this.content);
        },
        exec(cmd, value = null) {
            document.execCommand(cmd, false, value);
            this.$refs.editor.focus();
            this.onInput();
        },
    };
}
</script>