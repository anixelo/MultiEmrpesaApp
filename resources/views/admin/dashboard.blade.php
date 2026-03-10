<x-app-layout>
    @php
        $hasCompany = filled($company);

        $companyName = $hasCompany ? $company->name : 'Sin empresa asignada';
        $hasPromo = $hasCompany && $company->isInPromo() && $company->promoPlan();
        $promoPlan = $hasPromo ? $company->promoPlan() : null;

        $canUseNotas = $hasCompany && $company->canUseNotas();
        $hasEmpresas = $hasCompany ? $company->empresas()->exists() : false;

        $presupuestoStatusConfig = [
            'borrador' => [
                'label' => 'Borrador',
                'bar' => 'bg-gray-400',
            ],
            'enviado' => [
                'label' => 'Enviado',
                'bar' => 'bg-blue-500',
            ],
            'visto' => [
                'label' => 'Visto',
                'bar' => 'bg-purple-500',
            ],
            'aceptado' => [
                'label' => 'Aceptado',
                'bar' => 'bg-green-500',
            ],
            'rechazado' => [
                'label' => 'Rechazado',
                'bar' => 'bg-red-500',
            ],
        ];

        $estadoBadgeClasses = [
            'gray' => 'bg-gray-100 text-gray-700',
            'blue' => 'bg-blue-100 text-blue-700',
            'purple' => 'bg-purple-100 text-purple-700',
            'green' => 'bg-green-100 text-green-700',
            'red' => 'bg-red-100 text-red-700',
        ];

        $statCards = [
            [
                'label' => 'Presupuestos totales',
                'value' => $stats['total_presupuestos'] ?? 0,
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'iconWrap' => 'bg-violet-50',
                'iconColor' => 'text-violet-600',
                'href' => route('admin.presupuestos.index'),
            ],
            [
                'label' => 'Presupuestos aceptados',
                'value' => $stats['presupuestos_aceptados'] ?? 0,
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
                'href' => route('admin.presupuestos.index', ['estado' => 'aceptado']),
            ],
            [
                'label' => 'Presupuestos rechazados',
                'value' => $stats['presupuestos_rechazados'] ?? 0,
                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-red-50',
                'iconColor' => 'text-red-600',
                'href' => route('admin.presupuestos.index', ['estado' => 'rechazado']),
            ],
        ];

        $notaCards = [
            [
                'label' => 'Notas totales',
                'value' => $stats['total_notas'] ?? 0,
                'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                'iconWrap' => 'bg-amber-50',
                'iconColor' => 'text-amber-600',
                'href' => route('admin.notas.index'),
            ],
            [
                'label' => 'Con presupuesto',
                'value' => $stats['notas_con_presupuesto'] ?? 0,
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600',
                'href' => route('admin.notas.index', ['presupuesto' => 'con']),
            ],
            [
                'label' => 'Sin presupuesto',
                'value' => $stats['notas_sin_presupuesto'] ?? 0,
                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'iconWrap' => 'bg-orange-50',
                'iconColor' => 'text-orange-600',
                'href' => route('admin.notas.index', ['presupuesto' => 'sin']),
            ],
        ];

        $totalPresupuestos = max(($presupuestosByStatus ?? collect())->sum(), 1);
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel</h1>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $companyName }}
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                <span class="h-2 w-2 rounded-full bg-blue-500 animate-pulse"></span>
                Administrador
            </span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
        @unless($hasCompany)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-4 text-sm text-amber-800">
                <span class="font-semibold">Atención:</span>
                No tienes una empresa asignada. Contacta con el superadministrador.
            </div>
        @else
            @if($hasPromo)
                <section class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white shadow-lg">


                    <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center">


                        <div class="flex-1">
                            <p class="text-base font-bold">
                                Estás disfrutando del plan
                                <span class="text-yellow-300">{{ $promoPlan->name }}</span>
                                de forma gratuita
                            </p>
                            <p class="mt-1 text-sm text-indigo-100">
                                Tu promoción es válida hasta el
                                <strong>{{ $company->promo_ends_at->format('d \d\e F \d\e Y') }}</strong>.
                                ¡Aprovéchalo al máximo!
                            </p>
                        </div>

                        <a
                            href="{{ route('admin.subscription') }}"
                            class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white/20 px-4 py-2 text-xs font-semibold text-white transition hover:bg-white/30"
                        >
                            Ver suscripción
                        </a>
                    </div>
                </section>
            @endif

            @unless($hasEmpresas)
                <section class="flex flex-col gap-4 rounded-2xl border border-blue-200 bg-blue-50 p-6 sm:flex-row sm:items-center">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="font-semibold text-gray-900">
                            Registra tu empresa para comenzar a crear presupuestos
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Necesitas al menos una empresa registrada para poder gestionar presupuestos, clientes y servicios.
                        </p>
                    </div>

                    <a
                        href="{{ route('admin.empresas.create') }}"
                        class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        Crear empresa
                    </a>
                </section>
            @endunless

            <section class="space-y-4">
                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('admin.presupuestos.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                    >
                        <svg class="h-4 w-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Nuevo presupuesto
                    </a>

                    @if($canUseNotas)
                        <a
                            href="{{ route('admin.notas.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50"
                        >
                            <svg class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Nueva nota
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($statCards as $card)
                        <a href="{{ $card['href'] }}" class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md hover:border-indigo-100 transition">
                            <div class="rounded-xl p-3 {{ $card['iconWrap'] }}">
                                <svg class="h-6 w-6 {{ $card['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                                <p class="mt-0.5 text-xs text-gray-500">{{ $card['label'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>

            <section class="grid grid-cols-1 gap-6">
                <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <div class="mb-5 flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Presupuestos por estado</h2>
                        <span class="text-xs text-gray-400">Resumen general</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($presupuestoStatusConfig as $estado => $config)
                            @php
                                $count = $presupuestosByStatus[$estado] ?? 0;
                                $percentage = round(($count / $totalPresupuestos) * 100);
                            @endphp

                            <div>
                                <div class="mb-1.5 flex items-center justify-between text-xs">
                                    <span class="text-gray-600">{{ $config['label'] }}</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $count }} <span class="text-gray-400">({{ $percentage }}%)</span>
                                    </span>
                                </div>

                                <div class="h-2 w-full rounded-full bg-gray-100">
                                    <div
                                        class="h-2 rounded-full transition-all duration-500 {{ $config['bar'] }}"
                                        style="width: {{ $percentage }}%"
                                    ></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>

                @if($recentPresupuestos->isNotEmpty())
                    <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Últimos presupuestos</h2>
                            <a href="{{ route('admin.presupuestos.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                                Ver todos
                            </a>
                        </div>

                        <div class="hidden overflow-x-auto md:block">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Número</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 bg-white">
                                    @foreach($recentPresupuestos as $p)
                                        @php
                                            $badgeClass = $estadoBadgeClasses[$p->estado_color] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <tr class="hover:bg-gray-50/60">
                                            <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-gray-900">
                                                {{ $p->numero }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                                {{ $p->cliente?->nombre ?? '—' }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-500">
                                                {{ $p->fecha->format('d/m/Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3">
                                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }}">
                                                    {{ $p->estado_label }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-right text-sm font-medium text-gray-900">
                                                {{ number_format($p->total, 2, ',', '.') }} €
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.presupuestos.show', $p->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                                                    <a href="{{ route('admin.presupuestos.edit', $p->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">Editar</a>
                                                    {{-- Three-dot dropdown --}}
                                                    <div x-data="{ open: false, dy: 0, dx: 0 }" @click.outside="open = false" @scroll.window="open = false" class="relative">
                                                        <button type="button"
                                                                @click="const r = $el.getBoundingClientRect(); const mh = 260; dy = (r.bottom + mh > window.innerHeight) ? r.top - mh : r.bottom; dx = r.right - 208; open = !open"
                                                                class="p-1 rounded text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none"
                                                                title="Más acciones">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                                            </svg>
                                                        </button>
                                                        <div x-show="open" x-transition
                                                             :style="'position:fixed;top:' + dy + 'px;left:' + dx + 'px'"
                                                             class="w-52 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-[9999]">
                                                            <a href="{{ route('admin.presupuestos.pdf', $p->id) }}"
                                                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                                Descargar PDF
                                                            </a>
                                                            @if ($p->cliente?->telefono)
                                                            @php
                                                                $phone = preg_replace('/[^0-9]/', '', $p->cliente->telefono);
                                                                if (strlen($phone) === 9) $phone = '34' . $phone;
                                                                $publicUrl = route('presupuestos.public', $p->token_publico);
                                                                $waText = urlencode("Hola {$p->cliente->nombre}, te enviamos el presupuesto {$p->numero}: {$publicUrl}");
                                                            @endphp
                                                            <a href="https://wa.me/{{ $phone }}?text={{ $waText }}"
                                                               target="_blank"
                                                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                                Enviar por WhatsApp
                                                            </a>
                                                            @endif
                                                            @if ($p->cliente?->email)
                                                            <form method="POST" action="{{ route('admin.presupuestos.send-email', $p->id) }}">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                                    Enviar por Email
                                                                </button>
                                                            </form>
                                                            @endif
                                                            <a href="{{ route('presupuestos.public', $p->token_publico) }}"
                                                               target="_blank"
                                                               class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                                Ver enlace público
                                                            </a>
                                                            <div class="border-t border-gray-100 mt-1">
                                                                <form method="POST"
                                                                      action="{{ route('admin.presupuestos.destroy', $p->id) }}"
                                                                      onsubmit="return confirm('¿Seguro que deseas eliminar este presupuesto?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                        Eliminar
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divide-y divide-gray-50 md:hidden">
                            @foreach($recentPresupuestos as $p)
                                @php
                                    $badgeClass = $estadoBadgeClasses[$p->estado_color] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <div class="p-4 space-y-2">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $p->numero }}</span>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $p->cliente?->nombre ?? '—' }} · {{ $p->fecha->format('d/m/Y') }}</p>
                                            <p class="mt-1 text-sm font-medium text-gray-900">{{ number_format($p->total, 2, ',', '.') }} €</p>
                                        </div>
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $badgeClass }} shrink-0">{{ $p->estado_label }}</span>
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex items-center gap-2">
                                        <a href="{{ route('admin.presupuestos.show', $p->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                                        <a href="{{ route('admin.presupuestos.edit', $p->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">Editar</a>
                                        <a href="{{ route('admin.presupuestos.pdf', $p->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">PDF</a>
                                        <a href="{{ route('presupuestos.public', $p->token_publico) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 transition">Enlace</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif


                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Notas</h2>
                        
                    </div>


                @if($canUseNotas)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach($notaCards as $card)
                            <a href="{{ $card['href'] }}" class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md hover:border-indigo-100 transition">
                                <div class="rounded-xl p-3 {{ $card['iconWrap'] }}">
                                    <svg class="h-6 w-6 {{ $card['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $card['label'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if($canUseNotas && isset($recentNotas) && $recentNotas->isNotEmpty())
                    <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                            <h2 class="text-base font-semibold text-gray-900">Últimas notas</h2>
                            <a href="{{ route('admin.notas.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                                Ver todas
                            </a>
                        </div>

                        <div class="hidden overflow-x-auto md:block">
                            <table class="min-w-full divide-y divide-gray-100">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Título</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Presupuesto</th>
                                        <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                                        <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 bg-white">
                                    @foreach($recentNotas as $n)
                                        <tr class="hover:bg-gray-50/60">
                                            <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-gray-900">
                                                {{ $n->titulo }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                                {{ $n->cliente?->nombre ?? '—' }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3">
                                                @if($n->presupuesto_id)
                                                    <a href="{{ route('admin.presupuestos.show', $n->presupuesto_id) }}"
                                                       class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-900">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        {{ $n->presupuesto?->numero ?? '—' }}
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.notas.crear-presupuesto', $n->id) }}"
                                                       class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                                        Crear presupuesto
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-500">
                                                {{ $n->created_at->format('d/m/Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-5 py-3 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="{{ route('admin.notas.show', $n->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                                                    <a href="{{ route('admin.notas.edit', $n->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">Editar</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divide-y divide-gray-50 md:hidden">
                            @foreach($recentNotas as $n)
                                <div class="p-4 space-y-2">
                                    <div>
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ $n->titulo }}</p>
                                        <p class="mt-0.5 text-xs text-gray-500">{{ $n->cliente?->nombre ?? '—' }} · {{ $n->created_at->format('d/m/Y') }}</p>
                                        @if($n->presupuesto_id)
                                            <a href="{{ route('admin.presupuestos.show', $n->presupuesto_id) }}" class="mt-1 inline-flex items-center gap-1 text-xs text-indigo-600 font-medium">
                                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                {{ $n->presupuesto?->numero ?? '—' }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.notas.crear-presupuesto', $n->id) }}" class="mt-1 inline-flex items-center gap-1 text-xs text-emerald-600 font-medium">
                                                + Crear presupuesto
                                            </a>
                                        @endif
                                    </div>
                                    <div class="pt-2 border-t border-gray-100 flex gap-2">
                                        <a href="{{ route('admin.notas.show', $n->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                                        <a href="{{ route('admin.notas.edit', $n->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-amber-700 bg-amber-50 rounded-lg hover:bg-amber-100 transition">Editar</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @endif
            </section>
        @endunless

            @if(($incidentStats['total'] ?? 0) > 0)
                @php
                    $incidentCards = [
                        [
                            'label' => 'Total incidencias',
                            'value' => $incidentStats['total'],
                            'iconWrap' => 'bg-gray-50',
                            'iconColor' => 'text-gray-600',
                            'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',
                            'href' => route('admin.incidents.index'),
                        ],
                        [
                            'label' => 'Abiertas',
                            'value' => $incidentStats['open'],
                            'iconWrap' => 'bg-blue-50',
                            'iconColor' => 'text-blue-600',
                            'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                            'href' => route('admin.incidents.index', ['status' => 'open']),
                        ],
                        [
                            'label' => 'En progreso',
                            'value' => $incidentStats['in_progress'],
                            'iconWrap' => 'bg-orange-50',
                            'iconColor' => 'text-orange-600',
                            'icon' => 'M12 6v6l4 2',
                            'href' => route('admin.incidents.index', ['status' => 'in_progress']),
                        ],
                        [
                            'label' => 'Resueltas',
                            'value' => $incidentStats['resolved'],
                            'iconWrap' => 'bg-green-50',
                            'iconColor' => 'text-green-600',
                            'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'href' => route('admin.incidents.index', ['status' => 'resolved']),
                        ],
                    ];
                    $incidentStatusClasses = [
                        'open' => 'bg-blue-100 text-blue-800',
                        'in_review' => 'bg-yellow-100 text-yellow-800',
                        'in_progress' => 'bg-orange-100 text-orange-800',
                        'resolved' => 'bg-green-100 text-green-800',
                        'closed' => 'bg-gray-100 text-gray-600',
                    ];
                    $incidentPriorityClasses = [
                        'baja' => 'bg-gray-100 text-gray-600',
                        'media' => 'bg-blue-100 text-blue-800',
                        'alta' => 'bg-orange-100 text-orange-800',
                        'urgente' => 'bg-red-100 text-red-800',
                    ];
                @endphp

                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-semibold text-gray-900">Incidencias</h2>
                        <a href="{{ route('admin.incidents.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">Ver todas</a>
                    </div>

                    <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">
                        @foreach($incidentCards as $card)
                            <a href="{{ $card['href'] }}" class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm hover:shadow-md hover:border-indigo-100 transition">
                                <div class="rounded-xl p-3 {{ $card['iconWrap'] }}">
                                    <svg class="h-6 w-6 {{ $card['iconColor'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                                    <p class="mt-0.5 text-xs text-gray-500">{{ $card['label'] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    @if(isset($recentIncidents) && $recentIncidents->isNotEmpty())
                        <article class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                                <h2 class="text-base font-semibold text-gray-900">Últimas incidencias</h2>
                                <a href="{{ route('admin.incidents.index') }}" class="text-xs font-medium text-indigo-600 hover:underline">Ver todas</a>
                            </div>

                            <div class="hidden overflow-x-auto md:block">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Incidencia</th>
                                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Creada por</th>
                                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Prioridad</th>
                                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                                            <th class="px-5 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                                            <th class="px-5 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50 bg-white">
                                        @foreach($recentIncidents as $incident)
                                            @php
                                                $sClass = $incidentStatusClasses[$incident->status] ?? 'bg-gray-100 text-gray-600';
                                                $pClass = $incidentPriorityClasses[$incident->priority] ?? 'bg-gray-100 text-gray-600';
                                            @endphp
                                            <tr class="hover:bg-gray-50/60">
                                                <td class="whitespace-nowrap px-5 py-3 text-sm font-medium text-gray-900">
                                                    {{ Str::limit($incident->title, 50) }}
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-600">
                                                    {{ $incident->user?->name ?? '—' }}
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-3">
                                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $pClass }}">
                                                        {{ $incident->priority_label }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-3">
                                                    <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sClass }}">
                                                        {{ $incident->status_label }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-3 text-sm text-gray-500">
                                                    {{ $incident->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-5 py-3 text-right">
                                                    <a href="{{ route('incidents.show', $incident) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="divide-y divide-gray-50 md:hidden">
                                @foreach($recentIncidents as $incident)
                                    @php
                                        $sClass = $incidentStatusClasses[$incident->status] ?? 'bg-gray-100 text-gray-600';
                                        $pClass = $incidentPriorityClasses[$incident->priority] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <div class="p-4 space-y-2">
                                        <div>
                                            <p class="truncate text-sm font-semibold text-gray-900">{{ $incident->title }}</p>
                                            <p class="mt-0.5 text-xs text-gray-500">{{ $incident->user?->name ?? '—' }} · {{ $incident->created_at->format('d/m/Y') }}</p>
                                            <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sClass }}">{{ $incident->status_label }}</span>
                                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $pClass }}">{{ $incident->priority_label }}</span>
                                            </div>
                                        </div>
                                        <div class="pt-2 border-t border-gray-100 flex gap-2">
                                            <a href="{{ route('incidents.show', $incident) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endif
                </section>
            @endif

    </div>

</x-app-layout>