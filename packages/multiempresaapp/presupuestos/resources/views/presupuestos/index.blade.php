<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Presupuestos
                </h2>
                @if($maxPresupuestos > 0)
                <p class="text-sm text-gray-500 mt-0.5">&mdash; {{ $currentMonthCount }} / {{ $maxPresupuestos }} presupuestos este mes</p>
                @endif
            </div>
            <a href="{{ route('admin.presupuestos.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Nuevo Presupuesto
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            @if(!($hasEmpresa ?? true))
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <h3 class="font-semibold text-gray-900">Registra tu empresa para poder comenzar a crear presupuestos</h3>
                    <p class="text-sm text-gray-500 mt-1">Necesitas tener al menos una empresa registrada antes de crear presupuestos.</p>
                </div>
                <a href="{{ route('admin.empresas.create') }}"
                   class="shrink-0 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm">
                    Crear empresa
                </a>
            </div>
            @endif

            {{-- Búsqueda y filtros --}}
            <div class="mb-4">
                <form method="GET" action="{{ route('admin.presupuestos.index') }}" class="flex flex-wrap gap-2">
                    <input type="text"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por número o cliente..."
                           class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <select name="estado"
                            class="block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Todos los estados</option>
                        <option value="borrador"  {{ request('estado') === 'borrador'  ? 'selected' : '' }}>Borrador</option>
                        <option value="enviado"   {{ request('estado') === 'enviado'   ? 'selected' : '' }}>Enviado</option>
                        <option value="visto"     {{ request('estado') === 'visto'     ? 'selected' : '' }}>Visto</option>
                        <option value="aceptado"  {{ request('estado') === 'aceptado'  ? 'selected' : '' }}>Aceptado</option>
                        <option value="rechazado" {{ request('estado') === 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                        Filtrar
                    </button>
                    @if (request('buscar') || request('estado'))
                        <a href="{{ route('admin.presupuestos.index') }}"
                           class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                            Limpiar
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                {{-- Desktop table --}}
                <table class="hidden md:table min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Válido hasta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Total</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($presupuestos as $presupuesto)
                            @php
                                $colorMap = [
                                    'gray'   => 'bg-gray-100 text-gray-700',
                                    'blue'   => 'bg-blue-100 text-blue-700',
                                    'purple' => 'bg-purple-100 text-purple-700',
                                    'green'  => 'bg-green-100 text-green-700',
                                    'red'    => 'bg-red-100 text-red-700',
                                ];
                                $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <tr>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $presupuesto->numero }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                    {{ $presupuesto->negocio?->name ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                    {{ $presupuesto->cliente?->nombre ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $presupuesto->fecha->format('d/m/Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $presupuesto->validez_hasta?->format('d/m/Y') ?? '—' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badgeClass }}">
                                        {{ $presupuesto->estado_label }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    {{ number_format($presupuesto->total, 2, ',', '.') }} €
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                                           class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                        <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                                           class="text-yellow-600 hover:text-yellow-900">Editar</a>
                                        {{-- Three-dot actions menu --}}
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" @click.outside="open = false"
                                                    type="button"
                                                    class="p-1 rounded text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none"
                                                    title="Más acciones">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                                </svg>
                                            </button>
                                            <div x-show="open" x-transition
                                                 class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                                                {{-- Descargar PDF --}}
                                                <a href="{{ route('admin.presupuestos.pdf', $presupuesto->id) }}"
                                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    Descargar PDF
                                                </a>
                                                {{-- Enviar por WhatsApp --}}
                                                @if ($presupuesto->cliente?->telefono)
                                                @php
                                                    $phone = preg_replace('/[^0-9]/', '', $presupuesto->cliente->telefono);
                                                    if (strlen($phone) === 9) $phone = '34' . $phone;
                                                    $publicUrl = route('presupuestos.public', $presupuesto->token_publico);
                                                    $waText = urlencode("Hola {$presupuesto->cliente->nombre}, te enviamos el presupuesto {$presupuesto->numero}: {$publicUrl}");
                                                @endphp
                                                <a href="https://wa.me/{{ $phone }}?text={{ $waText }}"
                                                   target="_blank"
                                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                                    Enviar por WhatsApp
                                                </a>
                                                @endif
                                                {{-- Enviar por Email --}}
                                                @if ($presupuesto->cliente?->email)
                                                <form method="POST" action="{{ route('admin.presupuestos.send-email', $presupuesto->id) }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                        <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                        Enviar por Email
                                                    </button>
                                                </form>
                                                @endif
                                                {{-- Ver enlace público --}}
                                                <a href="{{ route('presupuestos.public', $presupuesto->token_publico) }}"
                                                   target="_blank"
                                                   class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                    Ver enlace público
                                                </a>
                                                <div class="border-t border-gray-100 mt-1">
                                                    <form method="POST"
                                                          action="{{ route('admin.presupuestos.destroy', $presupuesto->id) }}"
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
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                    No se encontraron presupuestos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile cards (bocadillos) --}}
                <div class="md:hidden divide-y divide-gray-100">
                    @forelse ($presupuestos as $presupuesto)
                    @php
                        $colorMap = [
                            'gray'   => 'bg-gray-100 text-gray-700',
                            'blue'   => 'bg-blue-100 text-blue-700',
                            'purple' => 'bg-purple-100 text-purple-700',
                            'green'  => 'bg-green-100 text-green-700',
                            'red'    => 'bg-red-100 text-red-700',
                        ];
                        $badgeClass = $colorMap[$presupuesto->estado_color] ?? 'bg-gray-100 text-gray-700';
                    @endphp
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $presupuesto->numero }}</p>
                                @if($presupuesto->negocio)
                                <p class="text-xs text-indigo-600 mt-0.5">{{ $presupuesto->negocio->name }}</p>
                                @endif
                                <p class="text-xs text-gray-600 mt-0.5">{{ $presupuesto->cliente?->nombre ?? '—' }}</p>
                            </div>
                            <span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $badgeClass }} shrink-0">
                                {{ $presupuesto->estado_label }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-2 text-xs text-gray-500">
                            <span>{{ $presupuesto->fecha->format('d/m/Y') }}</span>
                            @if($presupuesto->validez_hasta)
                            <span>Válido: {{ $presupuesto->validez_hasta->format('d/m/Y') }}</span>
                            @endif
                            <span class="font-semibold text-gray-900">{{ number_format($presupuesto->total, 2, ',', '.') }} €</span>
                        </div>
                        <div class="pt-2 border-t border-gray-100 flex items-center gap-2">
                            <a href="{{ route('admin.presupuestos.show', $presupuesto->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                            <a href="{{ route('admin.presupuestos.edit', $presupuesto->id) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">Editar</a>
                            {{-- Three-dot actions --}}
                            <div x-data="{ open: false }" class="relative ml-auto">
                                <button @click="open = !open" @click.outside="open = false"
                                        type="button"
                                        class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 focus:outline-none">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                                    </svg>
                                </button>
                                <div x-show="open" x-transition
                                     class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('admin.presupuestos.pdf', $presupuesto->id) }}"
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Descargar PDF
                                    </a>
                                    @if ($presupuesto->cliente?->telefono)
                                    @php
                                        $phone = preg_replace('/[^0-9]/', '', $presupuesto->cliente->telefono);
                                        if (strlen($phone) === 9) $phone = '34' . $phone;
                                        $publicUrl = route('presupuestos.public', $presupuesto->token_publico);
                                        $waText = urlencode("Hola {$presupuesto->cliente->nombre}, te enviamos el presupuesto {$presupuesto->numero}: {$publicUrl}");
                                    @endphp
                                    <a href="https://wa.me/{{ $phone }}?text={{ $waText }}"
                                       target="_blank"
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        Enviar por WhatsApp
                                    </a>
                                    @endif
                                    @if ($presupuesto->cliente?->email)
                                    <form method="POST" action="{{ route('admin.presupuestos.send-email', $presupuesto->id) }}">
                                        @csrf
                                        <button type="submit"
                                                class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                            Enviar por Email
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('presupuestos.public', $presupuesto->token_publico) }}"
                                       target="_blank"
                                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        Ver enlace público
                                    </a>
                                    <div class="border-t border-gray-100 mt-1">
                                        <form method="POST" action="{{ route('admin.presupuestos.destroy', $presupuesto->id) }}"
                                              onsubmit="return confirm('¿Seguro que deseas eliminar este presupuesto?')">
                                            @csrf @method('DELETE')
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
                    </div>
                    @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500">No se encontraron presupuestos.</div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4">
                {{ $presupuestos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
