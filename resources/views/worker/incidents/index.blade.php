<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Mis incidencias</h1>
                <p class="mt-0.5 text-sm text-slate-500">Seguimiento de tus incidencias enviadas</p>
            </div>

            <a href="{{ route('worker.incidents.create') }}"
               class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva incidencia
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <form method="GET" class="flex flex-wrap gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="rounded-2xl border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos los estados</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Abierta</option>
                <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>En revisión</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En progreso</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resuelta</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Cerrada</option>
            </select>

            @if(request('status'))
                <a href="{{ route('worker.incidents.index') }}"
                   class="rounded-2xl border border-slate-300 px-3 py-2 text-sm text-slate-500 transition hover:bg-slate-50">
                    Limpiar
                </a>
            @endif
        </form>

        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Listado de incidencias</h2>
                <span class="text-xs text-slate-400">Seguimiento personal</span>
            </div>

            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Incidencia</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Prioridad</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($incidents as $incident)
                                @php
                                    $statusColors = [
                                        'open' => 'bg-blue-100 text-blue-800',
                                        'in_review' => 'bg-yellow-100 text-yellow-800',
                                        'in_progress' => 'bg-orange-100 text-orange-800',
                                        'resolved' => 'bg-emerald-100 text-emerald-800',
                                        'closed' => 'bg-slate-100 text-slate-600',
                                    ];

                                    $priorityColors = [
                                        'baja' => 'bg-slate-100 text-slate-600',
                                        'media' => 'bg-blue-100 text-blue-800',
                                        'alta' => 'bg-orange-100 text-orange-800',
                                        'urgente' => 'bg-rose-100 text-rose-800',
                                    ];
                                @endphp

                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <div class="max-w-xs">
                                            <p class="truncate text-sm font-semibold text-slate-900">{{ $incident->title }}</p>
                                            <p class="mt-1 truncate text-xs text-slate-500">{{ Str::limit($incident->description, 60) }}</p>
                                        </div>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $priorityColors[$incident->priority] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $incident->priority_label }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusColors[$incident->status] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $incident->status_label }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-4 py-4 text-sm text-slate-500">
                                        {{ $incident->created_at->format('d/m/Y') }}
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <a href="{{ route('incidents.show', $incident) }}"
                                           class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center">
                                        <div class="text-slate-400">
                                            <svg class="mx-auto mb-3 w-12 h-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-sm">No has creado ninguna incidencia aún.</p>
                                            <a href="{{ route('worker.incidents.create') }}" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:underline">
                                                Crear primera incidencia
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-3 p-4 md:hidden">
                @forelse($incidents as $incident)
                    @php
                        $statusColors = [
                            'open' => 'bg-blue-100 text-blue-800',
                            'in_review' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-orange-100 text-orange-800',
                            'resolved' => 'bg-emerald-100 text-emerald-800',
                            'closed' => 'bg-slate-100 text-slate-600',
                        ];

                        $priorityColors = [
                            'baja' => 'bg-slate-100 text-slate-600',
                            'media' => 'bg-blue-100 text-blue-800',
                            'alta' => 'bg-orange-100 text-orange-800',
                            'urgente' => 'bg-rose-100 text-rose-800',
                        ];
                    @endphp

                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $incident->title }}</p>

                            @if($incident->description)
                                <p class="mt-1 text-xs text-slate-500">{{ Str::limit($incident->description, 80) }}</p>
                            @endif

                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $priorityColors[$incident->priority] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $incident->priority_label }}
                                </span>

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $statusColors[$incident->status] ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $incident->status_label }}
                                </span>

                                <span class="inline-flex items-center text-xs text-slate-400">
                                    {{ $incident->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2 border-t border-slate-100 pt-3">
                            <a href="{{ route('incidents.show', $incident) }}"
                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                Ver
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-slate-400">
                        <svg class="mx-auto mb-3 w-12 h-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm">No has creado ninguna incidencia aún.</p>
                        <a href="{{ route('worker.incidents.create') }}" class="mt-2 inline-block text-sm font-medium text-indigo-600 hover:underline">
                            Crear primera incidencia
                        </a>
                    </div>
                @endforelse
            </div>

            @if($incidents->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $incidents->links() }}
                </div>
            @endif
        </article>
    </div>
</x-app-layout>