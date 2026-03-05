<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel de Administración</h1>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $company ? $company->name : 'Sin empresa asignada' }}
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                Administrador
            </span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

        @if(!$company)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
            <strong>Atención:</strong> No tienes una empresa asignada. Contacta con el superadministrador.
        </div>
        @else

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php
            $cards = [
                ['label'=>'Trabajadores','value'=>$stats['total_workers'],'color'=>'blue','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label'=>'Tareas totales','value'=>$stats['total_tasks'],'color'=>'indigo','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label'=>'Pendientes','value'=>$stats['pending_tasks'],'color'=>'amber','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label'=>'Completadas','value'=>$stats['completed_tasks'],'color'=>'emerald','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            @endphp
            @foreach($cards as $card)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
                <div class="p-3 rounded-xl bg-{{ $card['color'] }}-50">
                    <svg class="w-6 h-6 text-{{ $card['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tasks by status --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-semibold text-gray-900 mb-4">Tareas por estado</h2>
                @php
                $statusColors = ['pendiente'=>'yellow','en_progreso'=>'blue','completada'=>'green','cancelada'=>'red'];
                $statusLabels = ['pendiente'=>'Pendiente','en_progreso'=>'En progreso','completada'=>'Completada','cancelada'=>'Cancelada'];
                $total = $tasksByStatus->sum() ?: 1;
                @endphp
                <div class="space-y-3">
                    @foreach($statusColors as $status => $color)
                    @php $count = $tasksByStatus[$status] ?? 0; $pct = round(($count/$total)*100); @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-600">{{ $statusLabels[$status] }}</span>
                            <span class="font-medium text-gray-800">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-900">Trabajadores recientes</h2>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentUsers as $member)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $member->name }}</p>
                                <p class="text-xs text-gray-400">{{ $member->email }}</p>
                            </div>
                        </div>
                        @if($member->roles->first())
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $member->roles->first()->name }}</span>
                        @endif
                    </div>
                    @empty
                    <p class="px-6 py-4 text-sm text-gray-400">No hay usuarios en esta empresa.</p>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
