<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Noticias</h1>
                <p class="text-sm text-gray-500 mt-0.5">Gestiona las noticias del sitio web</p>
            </div>
            <a href="{{ route('superadmin.noticias.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700 transition shadow-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Nueva Noticia
            </a>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8 space-y-4">

        @if(session('success'))
        <div class="rounded-xl bg-green-50 border border-green-200 p-4 text-green-800 text-sm">{{ session('success') }}</div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Noticia</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($noticias as $noticia)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($noticia->imagen)
                                <img src="{{ Storage::url($noticia->imagen) }}" alt="{{ $noticia->titulo }}"
                                     class="w-12 h-9 object-cover rounded-lg shrink-0">
                                @else
                                <div class="w-12 h-9 bg-gray-100 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $noticia->titulo }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $noticia->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $noticia->publicado ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $noticia->publicado ? 'Publicada' : 'Borrador' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $noticia->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                @if($noticia->publicado)
                                <a href="{{ route('noticias.show', $noticia->slug) }}" target="_blank"
                                   class="text-xs text-gray-500 hover:text-gray-700">Ver</a>
                                @endif
                                <a href="{{ route('superadmin.noticias.edit', $noticia) }}"
                                   class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Editar</a>
                                <form method="POST" action="{{ route('superadmin.noticias.destroy', $noticia) }}"
                                      onsubmit="return confirm('¿Eliminar esta noticia?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-400">
                            No hay noticias aún. <a href="{{ route('superadmin.noticias.create') }}" class="text-indigo-600 hover:underline">Crea la primera</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>{{ $noticias->links() }}</div>
    </div>
</x-app-layout>
