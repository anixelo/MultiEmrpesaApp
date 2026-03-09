<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel</h1>
                <p class="text-sm text-gray-500 mt-0.5">Visión global del sistema</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-xs font-semibold">
                <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                Superadmin
            </span>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-8">

{{-- Stats cards --}}
<div class="grid grid-cols-3 gap-4">
    @php
    $cards = [
        ['label'=>'Usuarios totales','value'=>$stats['total_users'],'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z','color'=>'indigo'],
        ['label'=>'Cuentas activas','value'=>$stats['active_companies'],'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4','color'=>'emerald'],
        ['label'=>'Total cuentas','value'=>$stats['total_companies'],'icon'=>'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','color'=>'sky'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 flex items-center sm:items-center justify-center sm:justify-start gap-4">
        
        <div class="p-3 hidden sm:block rounded-xl bg-{{ $card['color'] }}-50">
            <svg class="w-6 h-6 text-{{ $card['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/>
            </svg>
        </div>

        <div class="text-center sm:text-left">
            <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $card['value'] }}</p>
            <p class="text-[9px] sm:text-xs text-gray-500 mt-0.5">{{ $card['label'] }}</p>
        </div>

    </div>
    @endforeach
</div>

        {{-- Quick actions --}}
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('superadmin.companies.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Cuenta
            </a>
            <a href="{{ route('superadmin.users.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-50 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Nuevo Usuario
            </a>
        </div>

        {{-- Tables --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- Recent Users --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Últimos usuarios</h2>
                    <a href="{{ route('superadmin.users.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todos</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentUsers as $user)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 text-xs font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($user->roles->first())
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ $user->roles->first()->name }}</span>
                            @endif
                            <a href="{{ route('superadmin.users.edit', $user) }}" class="text-indigo-500 hover:text-indigo-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="px-6 py-4 text-sm text-gray-400">No hay usuarios aún.</p>
                    @endforelse
                </div>
            </div>

            {{-- Recent Companies --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900">Últimas cuentas</h2>
                    <a href="{{ route('superadmin.companies.index') }}" class="text-xs text-indigo-600 hover:underline">Ver todas</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse($recentCompanies as $company)
                    <div class="px-6 py-3 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $company->name }}</p>
                            <p class="text-xs text-gray-400">{{ $company->users_count }} usuario(s)</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $company->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $company->active ? 'Activa' : 'Inactiva' }}
                            </span>
                            <a href="{{ route('superadmin.companies.edit', $company) }}" class="text-indigo-500 hover:text-indigo-700">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="px-6 py-4 text-sm text-gray-400">No hay cuentas aún.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
