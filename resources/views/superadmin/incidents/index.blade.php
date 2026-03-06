<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Todas las Incidencias</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestión global de incidencias</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        {{-- Filters --}}
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="status" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Todos los estados</option>
                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Abierta</option>
                <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>En Revisión</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resuelta</option>
                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Cerrada</option>
            </select>
            @if(request('status') || request('company_id'))
            <a href="{{ route('superadmin.incidents.index') }}" class="px-3 py-2 text-sm text-gray-500 border border-gray-300 rounded-xl hover:bg-gray-50 transition">Limpiar</a>
            @endif
        </form>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Desktop table --}}
            <table class="hidden md:table min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Incidencia</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Creada por</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Prioridad</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($incidents as $incident)
                    @php
                        $statusColors = ['open'=>'bg-blue-100 text-blue-800','in_review'=>'bg-yellow-100 text-yellow-800','in_progress'=>'bg-orange-100 text-orange-800','resolved'=>'bg-green-100 text-green-800','closed'=>'bg-gray-100 text-gray-600'];
                        $priorityColors = ['baja'=>'bg-gray-100 text-gray-600','media'=>'bg-blue-100 text-blue-800','alta'=>'bg-orange-100 text-orange-800','urgente'=>'bg-red-100 text-red-800'];
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 truncate max-w-xs">{{ $incident->title }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600">{{ $incident->company?->name ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">{{ $incident->user->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$incident->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $incident->priority_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$incident->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $incident->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $incident->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('superadmin.incidents.show', $incident) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 text-sm">No hay incidencias.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Mobile cards (bocadillos) --}}
            <div class="md:hidden divide-y divide-gray-100">
                @forelse($incidents as $incident)
                @php
                    $statusColors = ['open'=>'bg-blue-100 text-blue-800','in_review'=>'bg-yellow-100 text-yellow-800','in_progress'=>'bg-orange-100 text-orange-800','resolved'=>'bg-green-100 text-green-800','closed'=>'bg-gray-100 text-gray-600'];
                    $priorityColors = ['baja'=>'bg-gray-100 text-gray-600','media'=>'bg-blue-100 text-blue-800','alta'=>'bg-orange-100 text-orange-800','urgente'=>'bg-red-100 text-red-800'];
                @endphp
                <div class="p-4 space-y-2">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $incident->title }}</p>
                        <div class="text-xs text-gray-500 mt-0.5 flex flex-wrap gap-2">
                            @if($incident->company)
                            <span>{{ $incident->company->name }}</span>
                            @endif
                            <span>Por: {{ $incident->user->name }}</span>
                        </div>
                        <div class="flex flex-wrap gap-1.5 mt-1.5">
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$incident->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $incident->priority_label }}
                            </span>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$incident->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $incident->status_label }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $incident->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-100 flex flex-wrap gap-2">
                        <a href="{{ route('superadmin.incidents.show', $incident) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400 text-sm">No hay incidencias.</div>
                @endforelse
            </div>

            @if($incidents->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $incidents->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
