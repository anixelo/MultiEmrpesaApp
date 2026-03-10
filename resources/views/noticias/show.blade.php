<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO --}}
    <title>{{ $noticia->titulo }} — {{ config('app.name') }}</title>
    <meta name="description" content="{{ $noticia->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 155) }}">
    <link rel="canonical" href="{{ route('noticias.show', $noticia->slug) }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="article">
    <meta property="og:title" content="{{ $noticia->titulo }}">
    <meta property="og:description" content="{{ $noticia->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 155) }}">
    <meta property="og:url" content="{{ route('noticias.show', $noticia->slug) }}">
    @if($noticia->imagen)
    <meta property="og:image" content="{{ Storage::url($noticia->imagen) }}">
    @endif
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="article:published_time" content="{{ $noticia->publicado_en?->toISOString() }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="{{ $noticia->imagen ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $noticia->titulo }}">
    <meta name="twitter:description" content="{{ $noticia->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 155) }}">
    @if($noticia->imagen)
    <meta name="twitter:image" content="{{ Storage::url($noticia->imagen) }}">
    @endif

    {{-- Schema.org --}}
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "headline": "{{ addslashes($noticia->titulo) }}",
        "description": "{{ addslashes($noticia->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 155)) }}",
        "datePublished": "{{ $noticia->publicado_en?->toISOString() }}",
        "dateModified": "{{ $noticia->updated_at->toISOString() }}",
        "publisher": {
            "@type": "Organization",
            "name": "{{ config('app.name') }}",
            "url": "{{ url('/') }}"
        }
        @if($noticia->imagen)
        ,"image": "{{ Storage::url($noticia->imagen) }}"
        @endif
    }
    </script>

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
            <a href="/" class="flex items-center gap-2 text-indigo-600 font-bold text-xl">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ config('app.name') }}
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
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <nav class="flex items-center gap-2 text-sm text-gray-500">
            <a href="/" class="hover:text-indigo-600 transition">Inicio</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <a href="/#noticias" class="hover:text-indigo-600 transition">Noticias</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-700 truncate max-w-xs">{{ $noticia->titulo }}</span>
        </nav>
    </div>
</div>

{{-- Article --}}
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <article>
        {{-- Header --}}
        <header class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <span class="inline-flex items-center gap-1.5 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/><path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/></svg>
                    Noticias
                </span>
                @if($noticia->publicado_en)
                <time class="text-xs text-gray-400" datetime="{{ $noticia->publicado_en->toISOString() }}">
                    {{ $noticia->publicado_en->format('d \d\e F \d\e Y') }}
                </time>
                @endif
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 leading-tight mb-4">
                {{ $noticia->titulo }}
            </h1>
            @if($noticia->meta_description)
            <p class="text-lg text-gray-600 leading-relaxed">{{ $noticia->meta_description }}</p>
            @endif
            {{-- Tags --}}
            @if($noticia->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach($noticia->tags as $tag)
                <a href="{{ route('noticias.tag', $tag->slug) }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition-colors">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    {{ $tag->nombre }}
                </a>
                @endforeach
            </div>
            @endif
        </header>

        {{-- Featured image --}}
        @if($noticia->imagen)
        <div class="mb-8 rounded-2xl overflow-hidden shadow-md">
            <img src="{{ Storage::url($noticia->imagen) }}"
                 alt="{{ $noticia->titulo }}"
                 class="w-full object-cover max-h-96">
        </div>
        @endif

        {{-- Content --}}
        <div class="max-w-none text-gray-700 text-base leading-relaxed
                    [&_h1]:text-3xl [&_h1]:font-bold [&_h1]:text-gray-900 [&_h1]:mt-6 [&_h1]:mb-3
                    [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-gray-900 [&_h2]:mt-5 [&_h2]:mb-2
                    [&_h3]:text-xl [&_h3]:font-bold [&_h3]:text-gray-900 [&_h3]:mt-4 [&_h3]:mb-2
                    [&_p]:my-3
                    [&_a]:text-indigo-600 [&_a]:no-underline hover:[&_a]:underline
                    [&_strong]:text-gray-900 [&_strong]:font-bold
                    [&_ul]:list-disc [&_ul]:pl-6 [&_ul]:my-3
                    [&_ol]:list-decimal [&_ol]:pl-6 [&_ol]:my-3
                    [&_li]:my-1
                    [&_blockquote]:border-l-4 [&_blockquote]:border-gray-300 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-gray-600 [&_blockquote]:my-4
                    [&_hr]:my-6 [&_hr]:border-gray-200">
            {!! $noticia->contenido !!}
        </div>

        {{-- Footer --}}
        <footer class="mt-10 pt-6 border-t border-gray-200">
            {{-- Tags at bottom --}}
            @if($noticia->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-5">
                @foreach($noticia->tags as $tag)
                <a href="{{ route('noticias.tag', $tag->slug) }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-indigo-100 hover:text-indigo-700 transition-colors">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/></svg>
                    {{ $tag->nombre }}
                </a>
                @endforeach
            </div>
            @endif
            <a href="/" class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:text-indigo-800 font-medium transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Volver al inicio
            </a>
        </footer>
    </article>

    {{-- Related news (same tags) --}}
    @if(isset($relacionadas) && $relacionadas->isNotEmpty())
    <aside class="mt-12 pt-8 border-t border-gray-200">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Noticias relacionadas</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relacionadas as $rel)
            <a href="{{ route('noticias.show', $rel->slug) }}"
               class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                @if($rel->imagen)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ Storage::url($rel->imagen) }}" alt="{{ $rel->titulo }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $rel->titulo }}</h3>
                    @if($rel->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($rel->tags->take(3) as $t)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-gray-100 text-gray-600">{{ $t->nombre }}</span>
                        @endforeach
                    </div>
                    @endif
                    @if($rel->publicado_en)
                    <time class="text-xs text-gray-400 mt-1 block">{{ $rel->publicado_en->format('d/m/Y') }}</time>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </aside>
    @endif

    {{-- Other news --}}
    @if($otrasNoticias->isNotEmpty())
    <aside class="mt-16">
        <h2 class="text-xl font-bold text-gray-900 mb-6">Más noticias</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($otrasNoticias as $otra)
            <a href="{{ route('noticias.show', $otra->slug) }}"
               class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                @if($otra->imagen)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ Storage::url($otra->imagen) }}" alt="{{ $otra->titulo }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 text-sm line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $otra->titulo }}</h3>
                    @if($otra->publicado_en)
                    <time class="text-xs text-gray-400 mt-1 block">{{ $otra->publicado_en->format('d/m/Y') }}</time>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </aside>
    @endif
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ config('app.name') }} — Sistema Multi-Empresa
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
