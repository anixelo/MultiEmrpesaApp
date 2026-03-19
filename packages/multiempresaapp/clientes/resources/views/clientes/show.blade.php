<x-app-layout>


    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.clientes.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0 flex-1">
                <h1 class="truncate text-2xl font-bold text-slate-900">{{ $cliente->nombre }}</h1>
                <p class="mt-1 text-sm text-slate-500">Ficha del cliente</p>
            </div>

            <div class="flex gap-2">
                {{-- Contact action icons --}}
                @if($cliente->telefono)
                    @php
                        $phone = preg_replace('/[^0-9+]/', '', $cliente->telefono);
                        $phoneIntl = strlen(preg_replace('/[^0-9]/', '', $cliente->telefono)) === 9
                            ? '34' . preg_replace('/[^0-9]/', '', $cliente->telefono)
                            : preg_replace('/[^0-9]/', '', $cliente->telefono);
                    @endphp
                    <a href="tel:{{ $cliente->telefono }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-emerald-600 shadow-sm transition hover:bg-emerald-50"
                       title="Llamar">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </a>
                    <a href="https://wa.me/{{ $phoneIntl }}"
                       target="_blank"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-emerald-500 shadow-sm transition hover:bg-emerald-50"
                       title="Enviar WhatsApp">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                @endif
                @if($cliente->email)
                    <a href="mailto:{{ $cliente->email }}"
                       class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-blue-500 shadow-sm transition hover:bg-blue-50"
                       title="Enviar email">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </a>
                @endif

                <a href="{{ route('admin.clientes.edit', $cliente) }}"
                   class="inline-flex items-center rounded-2xl bg-amber-500 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-amber-600">
                    Editar
                </a>
            </div>
        </div>
    </x-slot>




    <div class="py-6">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Client info card --}}
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <dl class="divide-y divide-slate-200">
                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-slate-500">Nombre</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $cliente->nombre }}</dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">
                            @if($cliente->email)
                                <a href="mailto:{{ $cliente->email }}" class="inline-flex items-center gap-1.5 text-blue-600 hover:underline">
                                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $cliente->email }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-slate-500">Teléfono</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">
                            @if($cliente->telefono)
                                @php
                                    $phoneIntl = strlen(preg_replace('/[^0-9]/', '', $cliente->telefono)) === 9
                                        ? '34' . preg_replace('/[^0-9]/', '', $cliente->telefono)
                                        : preg_replace('/[^0-9]/', '', $cliente->telefono);
                                @endphp
                                <div class="flex items-center gap-3">
                                    <a href="tel:{{ $cliente->telefono }}" class="inline-flex items-center gap-1.5 text-emerald-600 hover:underline">
                                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $cliente->telefono }}
                                    </a>
                                    <a href="https://wa.me/{{ $phoneIntl }}" target="_blank"
                                       class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 transition hover:bg-emerald-100">
                                        <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                        WhatsApp
                                    </a>
                                </div>
                            @else
                                —
                            @endif
                        </dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-slate-500">Notas</dt>
                        <dd class="mt-1 whitespace-pre-wrap text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $cliente->notas ?? '—' }}</dd>
                    </div>

                    <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                        <dt class="text-sm font-medium text-slate-500">Creado</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:col-span-2 sm:mt-0">{{ $cliente->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Presupuestos table --}}
            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                    <h3 class="text-sm font-semibold text-slate-900">Presupuestos</h3>
                    <span class="text-xs text-slate-400">{{ $presupuestos->count() }} resultado(s)</span>
                </div>

                @if($presupuestos->isEmpty())
                    <div class="px-6 py-8 text-center text-sm text-slate-400">
                        Este cliente no tiene presupuestos aún.
                    </div>
                @else
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
                    @endphp

                    {{-- Desktop table --}}
                    <div class="hidden md:block">
                        <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                            <table class="min-w-full border-separate border-spacing-y-3">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Número</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Empresa</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha</th>
                                        <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                        <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Total</th>
                                        <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($presupuestos as $presupuesto)
                                        @php
                                            $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-slate-100 text-slate-700';
                                        @endphp
                                        <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                            <td class="whitespace-nowrap rounded-l-2xl px-4 py-4 text-sm font-semibold text-slate-900">
                                                {{ $presupuesto->numero }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-700">
                                                {{ $presupuesto->negocio?->name ?? '—' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                                {{ $presupuesto->fecha->format('d/m/Y') }}
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
                                                <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                    Ver
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Mobile cards --}}
                    <div class="space-y-3 p-4 md:hidden">
                        @foreach($presupuestos as $presupuesto)
                            @php
                                $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-slate-100 text-slate-700';
                            @endphp
                            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-900">{{ $presupuesto->numero }}</p>
                                        @if($presupuesto->negocio)
                                            <p class="mt-1 text-xs text-indigo-600">{{ $presupuesto->negocio->name }}</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $presupuesto->estado_label }}
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <div class="text-xs text-slate-500">
                                        {{ $presupuesto->fecha->format('d/m/Y') }}
                                        <span class="ml-2 font-semibold text-slate-900">{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                                    </div>
                                    <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                                       class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                        Ver
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
