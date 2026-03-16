<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Analytics</h1>
                <p class="mt-1 text-sm text-slate-500">Registro de las últimas 100 visitas</p>
            </div>
            <a href="{{ route('superadmin.analytics.index') }}"
               class="inline-flex shrink-0 items-center rounded-2xl bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm ring-1 ring-slate-200 transition hover:bg-slate-50">
                ← Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-left text-xs font-medium uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-4 whitespace-nowrap">Fecha / Hora</th>
                                <th class="px-6 py-4">Página (path)</th>
                                <th class="px-6 py-4">IP</th>
                                <th class="px-6 py-4">Usuario</th>
                                <th class="px-6 py-4">Referer</th>
                                <th class="px-6 py-4">User Agent</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($visits as $visit)
                                <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50">
                                    <td class="px-6 py-3 text-xs text-slate-500 whitespace-nowrap">
                                        {{ $visit->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-3 font-mono text-xs text-slate-700 max-w-[200px] truncate">
                                        {{ $visit->path }}
                                    </td>
                                    <td class="px-6 py-3 text-xs text-slate-600">{{ $visit->ip }}</td>
                                    <td class="px-6 py-3 text-xs text-slate-600">
                                        {{ $visit->user?->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-xs text-slate-500 max-w-[160px] truncate"
                                        title="{{ $visit->referer }}">
                                        {{ $visit->referer ?: '—' }}
                                    </td>
                                    <td class="px-6 py-3 text-xs text-slate-400 max-w-[200px] truncate"
                                        title="{{ $visit->user_agent }}">
                                        {{ Str::limit($visit->user_agent, 60) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-400">
                                        No hay datos de visitas todavía.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
