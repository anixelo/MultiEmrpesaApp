<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index,follow">

    <title>{{ $categoria->titulo }} — {{ config('app.name') }}</title>
    <meta name="description" content="{{ $categoria->contenido ? \Illuminate\Support\Str::limit(strip_tags($categoria->contenido), 155) : 'Noticias sobre ' . $categoria->titulo . ' en ' . config('app.name') }}">
    <link rel="canonical" href="{{ route('noticias.categoria', $categoria->slug) }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased bg-white text-gray-900">

{{-- Navigation --}}
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
<a href="/" class="flex items-center gap-3 text-indigo-600 font-extrabold text-xl" aria-label="Ir a inicio">
    <img
        src="/pwa-icons/icon-192x192.png"
        alt="Logo {{ config('app.name') }}"
        class="w-8 h-8 shrink-0"
    >
    <span>{{ config('app.name') }}</span>
</a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="/#features" class="text-sm text-gray-600 hover:text-indigo-600 transition">Funcionalidades</a>
                <a href="/#pricing" class="text-sm text-gray-600 hover:text-indigo-600 transition">Precios</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Registrarse
                    </a>
                @endauth
            </nav>
        </div>
    </div>
</header>

{{-- Breadcrumb --}}
<div class="bg-gray-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="/" class="hover:text-indigo-600 transition">Inicio</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="/#noticias" class="hover:text-indigo-600 transition">Noticias</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700">{{ $categoria->titulo }}</span>
        </nav>
    </div>
</div>

{{-- Header --}}
<div class="bg-violet-50 border-b border-violet-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($categoria->imagen)
            <div class="flex justify-center mb-6">
                <img src="{{ Storage::url($categoria->imagen) }}"
                     alt="{{ $categoria->titulo }}"
                     class="h-32 w-auto rounded-2xl object-cover shadow-sm">
            </div>
        @endif
        <div class="text-center">
            <span class="inline-flex items-center gap-1.5 bg-violet-100 text-violet-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd"/><path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z"/></svg>
                Categoría
            </span>
            <h1 class="text-3xl font-bold text-gray-900">{{ $categoria->titulo }}</h1>
            @if($categoria->contenido)
                <p class="text-gray-600 mt-3 max-w-2xl mx-auto">{{ strip_tags($categoria->contenido) }}</p>
            @endif
            <p class="text-gray-500 mt-2">{{ trans_choice('{0} Sin noticias|{1} :count noticia|[2,*] :count noticias', $noticias->total()) }}</p>
        </div>
    </div>
</div>

{{-- News grid --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($noticias->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <p class="text-lg font-medium">No hay noticias en esta categoría.</p>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($noticias as $noticia)
            <a href="{{ route('noticias.show', $noticia->slug) }}"
               class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                @if($noticia->imagen)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ Storage::url($noticia->imagen) }}" alt="{{ $noticia->titulo }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-violet-50 to-indigo-50 flex items-center justify-center">
                    <svg class="w-10 h-10 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
                <div class="p-5">
                    <h2 class="font-semibold text-gray-900 text-base line-clamp-2 group-hover:text-indigo-600 transition-colors mb-2">
                        {{ $noticia->titulo }}
                    </h2>
                    @if($noticia->meta_description)
                    <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $noticia->meta_description }}</p>
                    @endif
                    <div class="flex items-center justify-between mt-2">
                        @if($noticia->publicado_en)
                        <time class="text-xs text-gray-400">{{ $noticia->publicado_en->format('d/m/Y') }}</time>
                        @endif
                        @if($noticia->tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1">
                            @foreach($noticia->tags->take(3) as $t)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                {{ $t->nombre }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($noticias->hasPages())
        <div class="mt-10">
            {{ $noticias->links() }}
        </div>
        @endif
    @endif
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ config('app.name') }} — By Anixelo
    </div>
</footer>

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}
</script>
</body>
</html>
