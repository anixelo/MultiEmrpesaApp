<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Notificaciones</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Tu centro de notificaciones</p>
            </div>

            @if(auth()->user()->unreadNotifications->count())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex shrink-0 items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Marcar todas como leídas
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-semibold text-slate-900">Listado de notificaciones</h2>
                    <span class="text-xs text-slate-400">{{ $notifications->total() }} resultado(s)</span>
                </div>
            </div>

            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $url = $data['url'] ?? '#';
                    $isRead = $notification->read_at !== null;

                    $iconClasses = match($data['type'] ?? '') {
                        'new_incident' => 'bg-blue-100 text-blue-600',
                        'incident_resolved' => 'bg-emerald-100 text-emerald-600',
                        'new_comment' => 'bg-amber-100 text-amber-600',
                        'plan_changed' => 'bg-violet-100 text-violet-600',
                        default => 'bg-slate-100 text-slate-500',
                    };
                @endphp

                <div class="border-b border-slate-100 px-4 py-4 last:border-0 sm:px-6 {{ !$isRead ? 'bg-indigo-50/40' : 'bg-white' }}">
                    <div class="rounded-2xl border {{ !$isRead ? 'border-indigo-100 bg-white' : 'border-slate-200 bg-slate-50' }} p-4 shadow-sm transition hover:bg-white hover:shadow-md">
                        <div class="flex items-start gap-4">
                            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $iconClasses }}">
                                @if(($data['type'] ?? '') === 'new_incident')
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                @elseif(($data['type'] ?? '') === 'incident_resolved')
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @elseif(($data['type'] ?? '') === 'new_comment')
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                @elseif(($data['type'] ?? '') === 'plan_changed')
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0z"/>
                                    </svg>
                                @else
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-sm text-slate-800 {{ !$isRead ? 'font-semibold' : 'font-medium' }}">
                                            {{ $data['message'] ?? 'Nueva notificación' }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>

                                    <div class="flex shrink-0 items-center gap-2">
                                        @if(!$isRead)
                                            <span class="h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                                        @endif

                                        @if($url !== '#')
                                            <a href="{{ $url }}"
                                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                Ver
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-16 text-center text-slate-400">
                    <svg class="mx-auto mb-3 h-12 w-12 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p class="text-sm">No tienes notificaciones.</p>
                </div>
            @endforelse

            @if($notifications->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>