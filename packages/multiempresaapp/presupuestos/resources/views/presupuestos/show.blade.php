<x-app-layout>



<x-slot name="header">
    @php
        $colorMap = [
            'gray'   => 'bg-slate-100 text-slate-700',
            'orange' => 'bg-orange-100 text-orange-700',
            'teal'   => 'bg-teal-100 text-teal-700',
            'blue'   => 'bg-blue-100 text-blue-700',
            'purple' => 'bg-violet-100 text-violet-700',
            'green'  => 'bg-emerald-100 text-emerald-700',
            'red'    => 'bg-rose-100 text-rose-700',
        ];
        $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-slate-100 text-slate-700';
    @endphp

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.presupuestos.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">
                    Presupuesto {{ $presupuesto->numero }}
                </h1>
            </div>
        </div>

        <span class="inline-flex w-fit rounded-full px-3 py-1 text-sm font-semibold {{ $badgeClass }}">
            {{ $presupuesto->estado_label }}
        </span>
    </div>
</x-slot>









    <div class="py-6">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Sección 1: Datos de la empresa --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Datos de la empresa</h3>
                </div>
                <div class="px-6 py-5">
                    @if ($presupuesto->negocio)
                        <p class="text-sm font-semibold text-slate-900">{{ $presupuesto->negocio->name }}</p>
                        @if ($presupuesto->negocio->nif)
                            <p class="mt-0.5 text-sm text-slate-500">NIF: {{ $presupuesto->negocio->nif }}</p>
                        @endif
                        @if ($presupuesto->negocio->email)
                            <p class="text-sm text-slate-500">{{ $presupuesto->negocio->email }}</p>
                        @endif
                        @if ($presupuesto->negocio->phone)
                            <p class="text-sm text-slate-500">{{ $presupuesto->negocio->phone }}</p>
                        @endif
                        @if ($presupuesto->negocio->address)
                            <p class="text-sm text-slate-500">{{ $presupuesto->negocio->address }}</p>
                        @endif
                    @else
                        <p class="text-sm italic text-slate-400">Sin empresa asignada.</p>
                    @endif
                </div>
            </div>

            {{-- Sección 2: Datos del cliente --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Datos del cliente</h3>
                </div>
                <div class="px-6 py-5">
                    @if ($presupuesto->cliente)
                        <p class="text-sm font-semibold text-slate-900">{{ $presupuesto->cliente->nombre }}</p>
                        @if ($presupuesto->cliente->email)
                            <p class="text-sm text-slate-500">{{ $presupuesto->cliente->email }}</p>
                        @endif
                        @if ($presupuesto->cliente->telefono)
                            <p class="text-sm text-slate-500">{{ $presupuesto->cliente->telefono }}</p>
                        @endif
                        @if ($presupuesto->cliente->direccion)
                            <p class="text-sm text-slate-500">{{ $presupuesto->cliente->direccion }}</p>
                        @endif
                    @else
                        <p class="text-sm italic text-slate-400">Sin cliente asignado.</p>
                    @endif
                </div>
            </div>

            {{-- Sección 3: Datos generales --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Datos generales</h3>
                </div>
                <div class="grid grid-cols-2 gap-6 px-6 py-5 sm:grid-cols-4">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Número</dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $presupuesto->numero }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Fecha</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $presupuesto->fecha->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Válido hasta</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            {{ $presupuesto->validez_hasta?->format('d/m/Y') ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Forma de pago</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $presupuesto->forma_pago ?? '—' }}</dd>
                    </div>

                    @if ($presupuesto->observaciones)
                        <div class="col-span-2 sm:col-span-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Observaciones</dt>
                            <dd class="prose prose-sm mt-1 max-w-none text-slate-700">{!! $presupuesto->observaciones !!}</dd>
                        </div>
                    @endif

                    @if ($presupuesto->notas)
                        <div class="col-span-2 sm:col-span-4">
                            <dt class="text-xs font-medium uppercase tracking-wider text-slate-500">Notas internas</dt>
                            <dd class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $presupuesto->notas }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sección 4: Líneas de presupuesto --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Líneas de presupuesto</h3>
                </div>

                {{-- Desktop table --}}
                <div class="hidden md:block">
                    <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                        <table class="min-w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Concepto</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Cant.</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">P. unit.</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Descuento</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Base imp.</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">IVA</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presupuesto->lineas as $linea)
                                    <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                        <td class="rounded-l-2xl px-4 py-4 text-sm text-slate-900">
                                            {{ $linea->concepto }}
                                            @if ($linea->servicio)
                                                <span class="ml-1 text-xs text-slate-400">({{ $linea->servicio->nombre }})</span>
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
                                            ({{ number_format($linea->iva_cuota, 2, ',', '.') }} €)
                                        </td>
                                        <td class="rounded-r-2xl px-4 py-4 text-right text-sm font-semibold text-slate-900">
                                            {{ number_format($linea->total, 2, ',', '.') }} €
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Mobile cards --}}
                <div class="space-y-3 p-4 md:hidden">
                    @foreach ($presupuesto->lineas as $linea)
                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <p class="text-sm font-semibold text-slate-900">{{ $linea->concepto }}</p>

                            <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1 text-sm">
                                <div class="text-slate-500">Cantidad</div>
                                <div class="text-right text-slate-700">{{ number_format($linea->cantidad, 2, ',', '.') }}</div>

                                <div class="text-slate-500">P. unit.</div>
                                <div class="text-right text-slate-700">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</div>

                                @if ($linea->descuento_tipo && $linea->descuento_valor)
                                    <div class="text-slate-500">Descuento</div>
                                    <div class="text-right text-slate-700">
                                        @if ($linea->descuento_tipo === 'porcentaje')
                                            {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                                        @else
                                            {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                                        @endif
                                    </div>
                                @endif

                                <div class="text-slate-500">Base imp.</div>
                                <div class="text-right text-slate-700">{{ number_format($linea->base_imponible, 2, ',', '.') }} €</div>

                                <div class="text-slate-500">IVA</div>
                                <div class="text-right text-slate-700">{{ number_format($linea->iva_tipo, 0) }}% ({{ number_format($linea->iva_cuota, 2, ',', '.') }} €)</div>
                            </div>

                            <div class="mt-3 flex justify-end border-t border-slate-100 pt-2">
                                <span class="text-sm font-semibold text-slate-900">Total: {{ number_format($linea->total, 2, ',', '.') }} €</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Totales --}}
                <div class="border-t border-slate-100 px-6 py-5">
                    <div class="ml-auto max-w-xs space-y-1">
                        <div class="flex justify-between text-sm text-slate-600">
                            <span>Subtotal bruto</span>
                            <span>{{ number_format($presupuesto->subtotal_bruto, 2, ',', '.') }} €</span>
                        </div>
                        @if ($presupuesto->subtotal_descuentos > 0)
                            <div class="flex justify-between text-sm text-rose-600">
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
                        <div class="flex justify-between border-t border-slate-300 pt-2 text-base font-bold text-slate-900">
                            <span>TOTAL</span>
                            <span>{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nota de revisión --}}
            @if ($presupuesto->nota_revision)
                <div class="overflow-hidden rounded-3xl border border-amber-200 bg-amber-50 shadow-sm">
                    <div class="border-b border-amber-100 px-6 py-4">
                        <h3 class="text-sm font-semibold text-amber-900">Nota del administrador</h3>
                    </div>
                    <div class="px-6 py-5">
                        <p class="text-sm text-amber-800">{{ $presupuesto->nota_revision }}</p>
                    </div>
                </div>
            @endif

            {{-- Acciones --}}
            @php
                $esBorrador          = $presupuesto->estado === 'borrador';
                $esPendienteRevision = $presupuesto->estado === 'pendiente_revision';
                $esValidado          = $presupuesto->estado === 'validado';
                $workerConRevision   = $isWorker && $revisarPresupuestos;
                // Worker with revisar_presupuestos: in borrador, can only view/edit/delete/request-review; can't send/download/whatsapp/email
                // In validado: can only view (no edit/delete)
                $workerCanSend       = ! $isWorker || ! $revisarPresupuestos || $esValidado;
            @endphp
            <div x-data="{
                showModal: false,
                pendingAction: null,
                pendingUrl: null,
                pendingForm: null,
                trigger(action, url = null, formId = null) {
                    if (!@json($esBorrador)) {
                        if (action === 'whatsapp' && url) window.open(url, '_blank');
                        else if (action === 'pdf' && url) window.location.href = url;
                        else if (formId) document.getElementById(formId).submit();
                        return;
                    }
                    this.pendingAction = action;
                    this.pendingUrl = url;
                    this.pendingForm = formId;
                    this.showModal = true;
                },
                async confirm(markSent) {
                    this.showModal = false;
                    const doAction = () => {
                        if (this.pendingAction === 'whatsapp' && this.pendingUrl) window.open(this.pendingUrl, '_blank');
                        else if (this.pendingAction === 'pdf' && this.pendingUrl) window.location.href = this.pendingUrl;
                        else if (this.pendingForm) document.getElementById(this.pendingForm).submit();
                    };
                    if (markSent) {
                        const form = document.getElementById('form-marcar-enviado');
                        const data = new FormData(form);
                        try {
                            const resp = await fetch(form.action, {method: 'POST', body: data, redirect: 'follow'});
                            if (!resp.ok) { console.error('Error al marcar como enviado'); }
                        } catch (e) {
                            console.error('Error al marcar como enviado', e);
                        }
                        doAction();
                    } else {
                        doAction();
                    }
                }
            }" class="flex flex-wrap justify-center gap-3">

                <form id="form-marcar-enviado" method="POST" action="{{ route('admin.presupuestos.enviar', $presupuesto->id) }}" class="hidden">
                    @csrf
                </form>

                {{-- Editar: hidden for workers on validado/pendiente_revision --}}
                @if (! ($isWorker && ($esValidado || $esPendienteRevision)))
                    <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                       class="inline-flex items-center rounded-2xl bg-amber-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-600">
                        Editar
                    </a>
                @endif

                {{-- Solicitar revisión: only workers with revisar_presupuestos in borrador --}}
                @if ($workerConRevision && $esBorrador)
                    <form method="POST" action="{{ route('admin.presupuestos.solicitar-revision', $presupuesto->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-2xl bg-orange-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-orange-600">
                            Solicitar revisión
                        </button>
                    </form>
                @endif

                {{-- Marcar como enviado: not for workers with revisar_presupuestos (unless validado) --}}
                @if ($workerCanSend && in_array($presupuesto->estado, ['borrador', 'visto', 'validado']))
                    <form method="POST" action="{{ route('admin.presupuestos.enviar', $presupuesto->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                            Marcar como enviado
                        </button>
                    </form>
                @endif

                {{-- Validar/Rechazar revisión: only admins on pendiente_revision --}}
                @if ($isAdmin && $esPendienteRevision)
                    <div x-data="{ showValidarModal: false, showRechazarModal: false }">
                        <button type="button" @click="showValidarModal = true"
                                class="inline-flex items-center rounded-2xl bg-teal-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-teal-700">
                            Validar
                        </button>
                        <button type="button" @click="showRechazarModal = true"
                                class="inline-flex items-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-rose-700">
                            Rechazar revisión
                        </button>

                        {{-- Validar modal --}}
                        <div x-show="showValidarModal" x-transition.opacity
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                             @click.self="showValidarModal = false">
                            <div class="mx-4 w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl">
                                <h3 class="mb-4 text-base font-semibold text-slate-900">Validar presupuesto</h3>
                                <form method="POST" action="{{ route('admin.presupuestos.validar-revision', $presupuesto->id) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Nota (opcional)</label>
                                        <textarea name="nota_revision" rows="3"
                                                  class="w-full rounded-2xl border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500"
                                                  placeholder="Añade una nota al trabajador..."></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="submit"
                                                class="flex-1 inline-flex items-center justify-center rounded-2xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-teal-700">
                                            Sí, validar
                                        </button>
                                        <button type="button" @click="showValidarModal = false"
                                                class="flex-1 inline-flex items-center justify-center rounded-2xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Rechazar revisión modal --}}
                        <div x-show="showRechazarModal" x-transition.opacity
                             class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                             @click.self="showRechazarModal = false">
                            <div class="mx-4 w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl">
                                <h3 class="mb-4 text-base font-semibold text-slate-900">Rechazar revisión</h3>
                                <form method="POST" action="{{ route('admin.presupuestos.rechazar-revision', $presupuesto->id) }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="mb-1 block text-sm font-medium text-slate-700">Nota (opcional)</label>
                                        <textarea name="nota_revision" rows="3"
                                                  class="w-full rounded-2xl border border-slate-300 px-3 py-2 text-sm focus:border-rose-500 focus:ring-rose-500"
                                                  placeholder="Indica el motivo del rechazo..."></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="submit"
                                                class="flex-1 inline-flex items-center justify-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                                            Sí, rechazar
                                        </button>
                                        <button type="button" @click="showRechazarModal = false"
                                                class="flex-1 inline-flex items-center justify-center rounded-2xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                                            Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Volver a borrador: only admins on validado --}}
                @if ($isAdmin && $esValidado)
                    <form method="POST" action="{{ route('admin.presupuestos.volver-borrador', $presupuesto->id) }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-2xl bg-slate-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-600">
                            Volver a borrador
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.presupuestos.duplicar', $presupuesto->id) }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center rounded-2xl bg-slate-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-slate-700">
                        Duplicar
                    </button>
                </form>

                {{-- Descargar PDF: restricted for workers with revisar_presupuestos unless validado --}}
                @if ($workerCanSend)
                <button type="button"
                        @click="trigger('pdf', '{{ route('admin.presupuestos.pdf', $presupuesto->id) }}')"
                        class="inline-flex items-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-rose-700">
                    <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Descargar PDF
                </button>
                @endif

                {{-- Enviar por WhatsApp: restricted for workers with revisar_presupuestos unless validado --}}
                @if ($canUseEnvioEnlace && $presupuesto->cliente?->telefono && $workerCanSend)
                    @php
                        $phone = preg_replace('/[^0-9]/', '', $presupuesto->cliente->telefono);
                        if (strlen($phone) === 9) $phone = '34' . $phone;
                        $publicUrl = route('presupuestos.public', $presupuesto->token_publico);
                        $waText = urlencode("Hola {$presupuesto->cliente->nombre}, te enviamos el presupuesto {$presupuesto->numero}: {$publicUrl}");
                    @endphp
                    <button type="button"
                            @click="trigger('whatsapp', 'https://wa.me/{{ $phone }}?text={{ $waText }}')"
                            class="inline-flex items-center rounded-2xl bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-emerald-700">
                        <svg class="mr-1.5 h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Enviar por WhatsApp
                    </button>
                @endif

                {{-- Enviar por Email: restricted for workers with revisar_presupuestos unless validado --}}
                @if ($canUseEnvioEnlace && $presupuesto->cliente?->email && $workerCanSend)
                    <form id="form-send-email" method="POST" action="{{ route('admin.presupuestos.send-email', $presupuesto->id) }}">
                        @csrf
                    </form>
                    <button type="button"
                            @click="trigger('email', null, 'form-send-email')"
                            class="inline-flex items-center rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                        <svg class="mr-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Enviar por Email
                    </button>
                @endif

                @if ($canUseEnvioEnlace && $workerCanSend)
                <a href="{{ route('presupuestos.public', $presupuesto->token_publico) }}"
                   target="_blank"
                   class="inline-flex items-center rounded-2xl bg-indigo-100 px-4 py-2.5 text-sm font-medium text-indigo-700 shadow-sm transition hover:bg-indigo-200">
                    Ver enlace público
                </a>
                @endif

                <a href="{{ route('admin.presupuestos.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Volver
                </a>

                {{-- Modal: marcar como enviado --}}
                @if($esBorrador || $esValidado)
                    <div x-show="showModal"
                         x-transition.opacity
                         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                         @click.self="showModal = false">
                        <div class="mx-4 w-full max-w-sm rounded-3xl bg-white p-6 shadow-2xl">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-blue-100">
                                    <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                </div>
                                <h3 class="text-base font-semibold text-slate-900">¿Marcar como enviado?</h3>
                            </div>

                            <p class="mb-5 text-sm text-slate-600">El presupuesto está en estado <strong>{{ $presupuesto->estado_label }}</strong>. ¿Deseas marcarlo como <strong>Enviado</strong>?</p>

                            <div class="flex gap-3">
                                <button type="button" @click="confirm(true)"
                                        class="flex-1 inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                                    Sí, marcar como enviado
                                </button>
                                <button type="button" @click="confirm(false)"
                                        class="flex-1 inline-flex items-center justify-center rounded-2xl bg-slate-100 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-200">
                                    No, continuar
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Historial de auditoría --}}
            @if ($canUseHistorialCambios)
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Historial de cambios</h3>
                </div>

                @php
                    $auditColors = [
                        'gray'   => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700'],
                        'yellow' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                        'blue'   => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                        'purple' => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700'],
                        'green'  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700'],
                        'red'    => ['bg' => 'bg-rose-100', 'text' => 'text-rose-700'],
                        'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                        'teal'   => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700'],
                    ];
                @endphp

                @if ($presupuesto->audits->isEmpty())
                    <div class="px-6 py-8 text-center text-sm text-slate-400">No hay registros de auditoría.</div>
                @else
                    {{-- Desktop table --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                            <table class="min-w-full border-separate border-spacing-y-3">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha y hora</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Usuario</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acción</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Descripción / cambios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($presupuesto->audits as $audit)
                                        @php
                                            $colors = $auditColors[$audit->accion_color] ?? $auditColors['gray'];
                                        @endphp
                                        <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                            <td class="whitespace-nowrap rounded-l-2xl px-4 py-4 text-sm text-slate-600">
                                                {{ $audit->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-4 py-4 text-sm text-slate-700">
                                                {{ $audit->usuario?->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                    {{ $audit->accion_label }}
                                                </span>
                                            </td>
                                            <td class="rounded-r-2xl px-4 py-4 text-sm text-slate-600">
                                                {{ $audit->descripcion ?? '—' }}
                                                @if ($audit->datos)
                                                    <ul class="mt-2 space-y-0.5 text-xs text-slate-500">
                                                        @foreach ($audit->datos as $campo => $vals)
                                                            <li>
                                                                <span class="font-medium">{{ $campo }}:</span>
                                                                <span class="text-rose-400 line-through">{{ $vals['antes'] ?? '—' }}</span>
                                                                → <span class="text-emerald-600">{{ $vals['despues'] ?? '—' }}</span>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="space-y-3 p-4 md:hidden">
                        @foreach ($presupuesto->audits as $audit)
                            @php
                                $colors = $auditColors[$audit->accion_color] ?? $auditColors['gray'];
                            @endphp
                            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $colors['bg'] }} {{ $colors['text'] }}">
                                        {{ $audit->accion_label }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $audit->created_at->format('d/m/Y H:i') }}</span>
                                </div>

                                <p class="mt-2 text-xs text-slate-500">
                                    <span class="font-medium text-slate-700">{{ $audit->usuario?->name ?? 'Sistema' }}</span>
                                    @if ($audit->descripcion) — {{ $audit->descripcion }} @endif
                                </p>

                                @if ($audit->datos)
                                    <ul class="mt-2 space-y-0.5 text-xs text-slate-500">
                                        @foreach ($audit->datos as $campo => $vals)
                                            <li>
                                                <span class="font-medium">{{ $campo }}:</span>
                                                <span class="text-rose-400 line-through">{{ $vals['antes'] ?? '—' }}</span>
                                                → <span class="text-emerald-600">{{ $vals['despues'] ?? '—' }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</x-app-layout>