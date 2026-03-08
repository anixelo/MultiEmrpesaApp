<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.noticias.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Noticia</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $noticia->titulo }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('superadmin.noticias.update', $noticia) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('superadmin.noticias._form', ['noticia' => $noticia])
            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('superadmin.noticias.index') }}"
                   class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
