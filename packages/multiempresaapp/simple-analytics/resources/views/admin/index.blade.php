<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Analytics</h1>
                <p class="mt-1 text-sm text-slate-500">Estadísticas de visitas a páginas</p>
            </div>
            <div class="flex shrink-0 gap-2">
                <a href="{{ route('superadmin.analytics.pages') }}"
                   class="inline-flex items-center rounded-2xl bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50">
                    Páginas
                </a>
                <a href="{{ route('superadmin.analytics.visits') }}"
                   class="inline-flex items-center rounded-2xl bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50">
                    Visitas
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Summary cards --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Hoy</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-600">{{ $visitsToday }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Ayer</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $visitsYesterday }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Este mes</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $visitsThisMonth }}</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-500">Total</p>
                    <p class="mt-2 text-3xl font-bold text-slate-800">{{ $totalVisits }}</p>
                </div>
            </div>

            {{-- Chart: visits by day --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-base font-semibold text-slate-800">Visitas últimos 30 días</h2>
                <canvas id="visitsChart" height="80"></canvas>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

                {{-- Top pages --}}
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h2 class="text-base font-semibold text-slate-800">Páginas más visitadas</h2>
                        <a href="{{ route('superadmin.analytics.pages') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <th class="px-6 py-3">Página</th>
                                    <th class="px-6 py-3 text-right">Visitas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topPages as $page)
                                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50">
                                        <td class="px-6 py-3 font-mono text-xs text-slate-700">{{ $page->path }}</td>
                                        <td class="px-6 py-3 text-right font-semibold text-slate-900">{{ $page->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-6 py-4 text-center text-sm text-slate-400">Sin datos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Latest visits --}}
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <h2 class="text-base font-semibold text-slate-800">Últimas visitas</h2>
                        <a href="{{ route('superadmin.analytics.visits') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                    <th class="px-6 py-3">Fecha</th>
                                    <th class="px-6 py-3">Página</th>
                                    <th class="px-6 py-3">IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($latestVisits as $visit)
                                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50">
                                        <td class="px-6 py-3 text-xs text-slate-500 whitespace-nowrap">
                                            {{ $visit->created_at->format('d/m H:i') }}
                                        </td>
                                        <td class="px-6 py-3 font-mono text-xs text-slate-700 max-w-[180px] truncate">
                                            {{ $visit->path }}
                                        </td>
                                        <td class="px-6 py-3 text-xs text-slate-500">{{ $visit->ip }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-slate-400">Sin datos</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('visitsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! $chartLabels !!},
                datasets: [{
                    label: 'Visitas',
                    data: {!! $chartData !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.6)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
