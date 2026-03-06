<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mis Incidencias</h1>
                <p class="text-sm text-gray-500 mt-0.5">Seguimiento de tus incidencias enviadas</p>
            </div>
            <a href="{{ route('worker.incidents.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Incidencia
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800 text-sm">{{ session('error') }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            {{-- Desktop table --}}
            <table class="hidden md:table min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Incidencia</th>
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
                            <div class="text-xs text-gray-400 mt-0.5">{{ Str::limit($incident->description, 60) }}</div>
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
                            <a href="{{ route('incidents.show', $incident) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                                Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-sm">No has creado ninguna incidencia aún.</p>
                            <a href="{{ route('worker.incidents.create') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">Crear primera incidencia</a>
                        </td>
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
                        @if($incident->description)
                        <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($incident->description, 80) }}</p>
                        @endif
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
                        <a href="{{ route('incidents.show', $incident) }}"
                           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">Ver</a>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <p class="text-sm">No has creado ninguna incidencia aún.</p>
                    <a href="{{ route('worker.incidents.create') }}" class="mt-2 inline-block text-sm text-indigo-600 hover:underline">Crear primera incidencia</a>
                </div>
                @endforelse
            </div>

            @if($incidents->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $incidents->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
