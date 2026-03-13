<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <button onclick="history.back()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </button>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900 truncate">{{ $incident->title }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">Incidencia #{{ $incident->id }} · {{ $incident->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </x-slot>

 
    

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-6">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        @php
            $statusColors = ['open'=>'bg-blue-100 text-blue-800','in_review'=>'bg-yellow-100 text-yellow-800','in_progress'=>'bg-orange-100 text-orange-800','resolved'=>'bg-green-100 text-green-800','closed'=>'bg-gray-100 text-gray-600'];
            $priorityColors = ['baja'=>'bg-gray-100 text-gray-600','media'=>'bg-blue-100 text-blue-800','alta'=>'bg-orange-100 text-orange-800','urgente'=>'bg-red-100 text-red-800'];
        @endphp

        {{-- Incident details --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$incident->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $incident->status_label }}
                    </span>
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold {{ $priorityColors[$incident->priority] ?? 'bg-gray-100 text-gray-600' }}">
                        Prioridad: {{ $incident->priority_label }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Por <span class="font-medium text-gray-700">{{ $incident->user->name }}</span>
                    @if($incident->company)
                    · <span class="text-gray-500">{{ $incident->company->name }}</span>
                    @endif
                </div>
            </div>
            <div class="mt-4 prose prose-sm max-w-none text-gray-700 whitespace-pre-wrap">{{ $incident->description }}</div>
        </div>

        {{-- Superadmin status control --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3">Cambiar Estado</h3>
            <form method="POST" action="{{ route('superadmin.incidents.update-status', $incident) }}" class="flex items-center gap-3 flex-wrap">
                @csrf
                <select name="status" class="border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="open" {{ $incident->status === 'open' ? 'selected' : '' }}>Abierta</option>
                    <option value="in_review" {{ $incident->status === 'in_review' ? 'selected' : '' }}>En Revisión</option>
                    <option value="in_progress" {{ $incident->status === 'in_progress' ? 'selected' : '' }}>En Progreso</option>
                    <option value="resolved" {{ $incident->status === 'resolved' ? 'selected' : '' }}>Resuelta</option>
                    <option value="closed" {{ $incident->status === 'closed' ? 'selected' : '' }}>Cerrada</option>
                </select>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    Actualizar Estado
                </button>
            </form>
        </div>
        @endif

        {{-- Comments --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Conversación ({{ $incident->comments->count() }})</h3>
            </div>

            <div class="divide-y divide-gray-50">
                @forelse($incident->comments as $comment)
                @php $isOwn = $comment->user_id === auth()->id(); @endphp
                <div class="px-6 py-4 flex gap-3 {{ $isOwn ? 'bg-indigo-50/50' : '' }}">
                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white text-xs font-bold
                        {{ $isOwn ? 'bg-indigo-500' : 'bg-gray-400' }}">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-sm font-semibold text-gray-800">{{ $comment->user->name }}</span>
                            @if($comment->user->isSuperAdmin())
                            <span class="text-xs bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded font-medium">Admin</span>
                            @elseif($comment->user->isAdmin())
                            <span class="text-xs bg-blue-100 text-blue-700 px-1.5 py-0.5 rounded font-medium">Empresa</span>
                            @endif
                            <span class="text-xs text-gray-400 ml-auto">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comment->comment }}</p>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">
                    Sé el primero en comentar esta incidencia.
                </div>
                @endforelse
            </div>

            {{-- Comment form --}}
            @if(!in_array($incident->status, ['closed']))
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                @php
                    $commentRoute = auth()->user()->isSuperAdmin()
                        ? route('superadmin.incidents.comment', $incident)
                        : route('incidents.comment', $incident);
                @endphp
                <form method="POST" action="{{ $commentRoute }}" class="flex gap-3">
                    @csrf
                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center bg-indigo-500 text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <textarea name="comment" rows="2"
                                  class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                  placeholder="Escribe un comentario..."></textarea>
                        @error('comment')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        <div class="mt-2 flex justify-end">
                            <button type="submit"
                                    class="px-4 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                                Enviar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            @else
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 text-center text-xs text-gray-400">
                Esta incidencia está cerrada. No se pueden añadir más comentarios.
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
