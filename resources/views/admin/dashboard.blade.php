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

        {{-- Promo banner --}}
        @if($company->isInPromo() && $company->promoPlan())
        @php $promoPlan = $company->promoPlan(); @endphp
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-5 text-white shadow-lg">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/></svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-base">
                        🎉 Estás disfrutando del plan <span class="text-yellow-300">{{ $promoPlan->name }}</span> de forma gratuita
                    </p>
                    <p class="text-indigo-100 text-sm mt-0.5">
                        Tu promoción es válida hasta el <strong>{{ $company->promo_ends_at->format('d \d\e F \d\e Y') }}</strong>.
                        ¡Aprovéchalo al máximo!
                    </p>
                </div>
                <a href="{{ route('admin.subscription') }}"
                   class="shrink-0 bg-white/20 hover:bg-white/30 text-white text-xs font-semibold px-4 py-2 rounded-lg transition">
                    Ver suscripción
                </a>
            </div>
        </div>
        @endif

        {{-- Empresa CTA --}}
        @if($company->empresas()->count() === 0)
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 flex flex-col sm:flex-row items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div class="flex-1 text-center sm:text-left">
                <h3 class="font-semibold text-gray-900">Registra tu empresa para comenzar a crear presupuestos</h3>
                <p class="text-sm text-gray-500 mt-1">Necesitas al menos una empresa registrada para poder gestionar presupuestos, clientes y servicios.</p>
            </div>
            <a href="{{ route('admin.empresas.create') }}"
               class="shrink-0 bg-blue-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-blue-700 transition shadow-sm">
                Crear empresa
            </a>
        </div>
        @endif

        {{-- Quick actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Gestionar Usuarios
            </a>
            @if($company->canUseTasks())
            <a href="{{ route('admin.tasks.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Gestionar Tareas
            </a>
            @else
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-400 cursor-default"
                  title="Actualiza tu plan para acceder a tareas">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Tareas (no incluido en tu plan)
            </span>
            @endif
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            @php
            $baseCards = [
                ['label'=>'Trabajadores','value'=>$stats['total_workers'] ?? 0,'color'=>'blue','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label'=>'Presupuestos totales','value'=>$stats['total_presupuestos'] ?? 0,'color'=>'violet','icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['label'=>'Presupuestos aceptados','value'=>$stats['presupuestos_aceptados'] ?? 0,'color'=>'emerald','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label'=>'Presupuestos rechazados','value'=>$stats['presupuestos_rechazados'] ?? 0,'color'=>'red','icon'=>'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            @endphp
            @foreach($baseCards as $card)
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

        {{-- Task stats (only if plan has tasks enabled) --}}
        @if($company->canUseTasks())
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
            @php
            $taskCards = [
                ['label'=>'Tareas totales','value'=>$stats['total_tasks'] ?? 0,'color'=>'indigo','icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label'=>'Pendientes','value'=>$stats['pending_tasks'] ?? 0,'color'=>'amber','icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label'=>'Completadas','value'=>$stats['completed_tasks'] ?? 0,'color'=>'emerald','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            @endphp
            @foreach($taskCards as $card)
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
        @endif

        {{-- Tasks by status --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            @if($company->canUseTasks())
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
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="font-semibold text-gray-900 mb-4">Presupuestos por estado</h2>
                @php
                $presupuestoColors = ['borrador'=>'gray','enviado'=>'blue','visto'=>'purple','aceptado'=>'green','rechazado'=>'red'];
                $presupuestoLabels = ['borrador'=>'Borrador','enviado'=>'Enviado','visto'=>'Visto','aceptado'=>'Aceptado','rechazado'=>'Rechazado'];
                $totalPresupuestos = ($presupuestosByStatus ?? collect())->sum() ?: 1;
                @endphp
                <div class="space-y-3">
                    @foreach($presupuestoColors as $estado => $color)
                    @php $count = ($presupuestosByStatus[$estado] ?? 0); $pct = round(($count/$totalPresupuestos)*100); @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-600">{{ $presupuestoLabels[$estado] }}</span>
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
