<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Presupuestos</h1>
                @if($maxPresupuestos > 0)
                    <p class="mt-1 text-sm text-slate-500">{{ $currentMonthCount }} / {{ $maxPresupuestos }} presupuestos este mes</p>
                @endif
            </div>

            <a href="{{ route('admin.presupuestos.create') }}"
               class="inline-flex shrink-0 items-center rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nuevo presupuesto
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if(!($hasEmpresa ?? true))
                <div class="mb-6 flex flex-col items-center gap-4 rounded-3xl border border-blue-200 bg-blue-50 p-6 shadow-sm sm:flex-row">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-100 shadow-inner">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="font-semibold text-slate-900">Registra tu empresa para poder comenzar a crear presupuestos</h3>
                        <p class="mt-1 text-sm text-slate-500">Necesitas tener al menos una empresa registrada antes de crear presupuestos.</p>
                    </div>

                    <a href="{{ route('admin.empresas.create') }}"
                       class="shrink-0 rounded-2xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Crear empresa
                    </a>
                </div>
            @endif

            <div class="mb-6">
                <form method="GET" action="{{ route('admin.presupuestos.index') }}" class="flex flex-col gap-3 lg:flex-row lg:items-center">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por número o cliente..."
                           class="block rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">

                    <select name="estado"
                            class="block rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100">
                        <option value="">Todos los estados</option>
                        <option value="borrador"  {{ request('estado') === 'borrador'  ? 'selected' : '' }}>Borrador</option>
                        <option value="enviado"   {{ request('estado') === 'enviado'   ? 'selected' : '' }}>Enviado</option>
                        <option value="visto"     {{ request('estado') === 'visto'     ? 'selected' : '' }}>Visto</option>
                        <option value="aceptado"  {{ request('estado') === 'aceptado'  ? 'selected' : '' }}>Aceptado</option>
                        <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-700 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                        Filtrar 
                    </button>

                    @if (request('buscar') || request('estado'))
                        <a href="{{ route('admin.presupuestos.index') }}"
                           class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Listado de presupuestos</h2>
                    <span class="text-xs text-slate-400">{{ $presupuestos->total() }} resultado(s)</span>
                </div>

                <div class="hidden md:block">
                    <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                        <table class="min-w-full border-separate border-spacing-y-3">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Número</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Empresa</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Cliente</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Válido hasta</th>
                                    <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Total</th>
                                    <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($presupuestos as $presupuesto)
                                    @php
                                        $colorMap = [
                                            'gray'   => 'bg-slate-100 text-slate-700',
                                            'blue'   => 'bg-blue-100 text-blue-700',
                                            'purple' => 'bg-violet-100 text-violet-700',
                                            'green'  => 'bg-emerald-100 text-emerald-700',
                                            'red'    => 'bg-rose-100 text-rose-700',
                                        ];
                                        $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-slate-100 text-slate-700';
                                    @endphp

                                    <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                        <td class="whitespace-nowrap rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                            {{ $presupuesto->numero }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                            {{ $presupuesto->negocio?->name ?? '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                            {{ $presupuesto->cliente?->nombre ?? '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $presupuesto->fecha->format('d/m/Y') }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                            {{ $presupuesto->validez_hasta?->format('d/m/Y') ?? '—' }}
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4">
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                {{ $presupuesto->estado_label }}
                                            </span>
                                        </td>

                                        <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-semibold text-slate-900">
                                            {{ number_format($presupuesto->total, 2, ',', '.') }} €
                                        </td>

                                        <td class="whitespace-nowrap rounded-r-2xl px-4 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Ver
                                                </a>

                                                <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                                    Editar
                                                </a>

                                                <div x-data="{ open: false, dy: 0, dx: 0 }" @click.outside="open = false" @scroll.window="open = false" class="relative">
                                                    <button type="button"
                                                            @click="const r = $el.getBoundingClientRect(); const mh = 260; dy = (r.bottom + mh > window.innerHeight) ? r.top - mh : r.bottom; dx = r.right - 208; open = !open"
                                                            class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700 focus:outline-none"
                                                            title="Más acciones">
                                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                                        </svg>
                                                    </button>

                                                    <div x-show="open" x-transition
                                                         :style="'position:fixed;top:' + dy + 'px;left:' + dx + 'px'"
                                                         class="z-[9999] w-52 rounded-2xl border border-slate-200 bg-white py-2 shadow-xl">
                                                        <a href="{{ route('admin.presupuestos.pdf', $presupuesto->id) }}"
                                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                            <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                            Descargar PDF
                                                        </a>

                                                        @if ($canUseEnvioEnlace)
                                                            @if ($presupuesto->cliente?->telefono)
                                                                @php
                                                                    $phone = preg_replace('/[^0-9]/', '', $presupuesto->cliente->telefono);
                                                                    if (strlen($phone) === 9) $phone = '34' . $phone;
                                                                    $publicUrl = route('presupuestos.public', $presupuesto->token_publico);
                                                                    $waText = urlencode("Hola {$presupuesto->cliente->nombre}, te enviamos el presupuesto {$presupuesto->numero}: {$publicUrl}");
                                                                @endphp
                                                                <a href="https://wa.me/{{ $phone }}?text={{ $waText }}"
                                                                   target="_blank"
                                                                   class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                                    <svg class="h-4 w-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                                    Enviar por WhatsApp
                                                                </a>
                                                            @endif

                                                            @if ($presupuesto->cliente?->email)
                                                                <form method="POST" action="{{ route('admin.presupuestos.send-email', $presupuesto->id) }}">
                                                                    @csrf
                                                                    <button type="submit"
                                                                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                                        Enviar por Email
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <a href="{{ route('presupuestos.public', $presupuesto->token_publico) }}"
                                                               target="_blank"
                                                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                                <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                                Ver enlace público
                                                            </a>
                                                        @endif

                                                        <div class="mt-1 border-t border-slate-100">
                                                            <form method="POST"
                                                                  action="{{ route('admin.presupuestos.destroy', $presupuesto->id) }}"
                                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este presupuesto?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                    Eliminar
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-10 text-center text-sm text-slate-500">
                                            No se encontraron presupuestos.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-3 p-4 md:hidden">
                    @forelse ($presupuestos as $presupuesto)
                        @php
                            $colorMap = [
                                'gray'   => 'bg-slate-100 text-slate-700',
                                'blue'   => 'bg-blue-100 text-blue-700',
                                'purple' => 'bg-violet-100 text-violet-700',
                                'green'  => 'bg-emerald-100 text-emerald-700',
                                'red'    => 'bg-rose-100 text-rose-700',
                            ];
                            $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-slate-100 text-slate-700';
                        @endphp

                        <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900">{{ $presupuesto->numero }}</p>

                                    @if($presupuesto->negocio)
                                        <p class="mt-1 text-xs text-indigo-600">{{ $presupuesto->negocio->name }}</p>
                                    @endif

                                    <p class="mt-1 text-xs text-slate-600">{{ $presupuesto->cliente?->nombre ?? '—' }}</p>
                                </div>

                                <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ $presupuesto->estado_label }}
                                </span>
                            </div>

                            <div class="mt-3 flex flex-wrap gap-2 text-xs text-slate-500">
                                <span>{{ $presupuesto->fecha->format('d/m/Y') }}</span>
                                @if($presupuesto->validez_hasta)
                                    <span>Válido: {{ $presupuesto->validez_hasta->format('d/m/Y') }}</span>
                                @endif
                                <span class="font-semibold text-slate-900">{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                            </div>

                            <div class="mt-3 flex items-center gap-2 border-t border-slate-100 pt-3">
                                <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                    Ver
                                </a>

                                <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                                   class="inline-flex items-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-medium text-amber-700 transition hover:bg-amber-100">
                                    Editar
                                </a>

                                <div x-data="{ open: false, dy: 0, dx: 0 }" @click.outside="open = false" @scroll.window="open = false" class="relative ml-auto">
                                    <button type="button"
                                            @click="const r = $el.getBoundingClientRect(); const mh = 260; dy = (r.bottom + mh > window.innerHeight) ? r.top - mh : r.bottom; dx = r.right - 208; open = !open"
                                            class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-700 focus:outline-none">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                        </svg>
                                    </button>

                                    <div x-show="open" x-transition
                                         :style="'position:fixed;top:' + dy + 'px;left:' + dx + 'px'"
                                         class="z-[9999] w-52 rounded-2xl border border-slate-200 bg-white py-2 shadow-xl">
                                        <a href="{{ route('admin.presupuestos.pdf', $presupuesto->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                            <svg class="h-4 w-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Descargar PDF
                                        </a>

                                        @if ($canUseEnvioEnlace)
                                            @if ($presupuesto->cliente?->telefono)
                                                @php
                                                    $phone = preg_replace('/[^0-9]/', '', $presupuesto->cliente->telefono);
                                                    if (strlen($phone) === 9) $phone = '34' . $phone;
                                                    $publicUrl = route('presupuestos.public', $presupuesto->token_publico);
                                                    $waText = urlencode("Hola {$presupuesto->cliente->nombre}, te enviamos el presupuesto {$presupuesto->numero}: {$publicUrl}");
                                                @endphp
                                                <a href="https://wa.me/{{ $phone }}?text={{ $waText }}"
                                                   target="_blank"
                                                   class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                    <svg class="h-4 w-4 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    Enviar por WhatsApp
                                                </a>
                                            @endif

                                            @if ($presupuesto->cliente?->email)
                                                <form method="POST" action="{{ route('admin.presupuestos.send-email', $presupuesto->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                        <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        Enviar por Email
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('presupuestos.public', $presupuesto->token_publico) }}"
                                               target="_blank"
                                               class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                                <svg class="h-4 w-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                Ver enlace público
                                            </a>
                                        @endif

                                        <div class="mt-1 border-t border-slate-100">
                                            <form method="POST" action="{{ route('admin.presupuestos.destroy', $presupuesto->id) }}"
                                                  onsubmit="return confirm('¿Seguro que deseas eliminar este presupuesto?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-slate-500">No se encontraron presupuestos.</div>
                    @endforelse
                </div>
            </section>

            <div class="mt-4">
                {{ $presupuestos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>