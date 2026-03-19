<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Home --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Category pages --}}
    @foreach($categorias as $categoria)
    <url>
        <loc>{{ route('noticias.categoria', $categoria->slug) }}</loc>
        <lastmod>{{ $categoria->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach

    {{-- Individual news articles --}}
    @foreach($noticias as $noticia)
    <url>
        <loc>{{ route('noticias.show', $noticia->slug) }}</loc>
        <lastmod>{{ $noticia->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
