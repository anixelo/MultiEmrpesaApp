<?php

namespace MultiempresaApp\Noticias\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use MultiempresaApp\Noticias\Models\Categoria;
use MultiempresaApp\Noticias\Models\Noticia;
use MultiempresaApp\Noticias\Models\Tag;

class NoticiaController extends Controller
{
    public function show(string $categoria, string $slug)
    {
        $noticia = Noticia::with('tags', 'categoria')->publicadas()->where('slug', $slug)->firstOrFail();

        // Canonical redirect if the categoria slug in the URL doesn't match
        if ($noticia->categoria && $noticia->categoria->slug !== $categoria) {
            return redirect()->route('noticias.show', [$noticia->categoria->slug, $slug], 301);
        }

        // Related news: same tags first, then fill with latest
        $tagIds = $noticia->tags->pluck('id');

        if ($tagIds->isNotEmpty()) {
            $relacionadas = Noticia::with('tags', 'categoria')
                ->publicadas()
                ->whereHas('categoria')
                ->where('id', '!=', $noticia->id)
                ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds))
                ->latest('publicado_en')
                ->take(3)
                ->get();
        } else {
            $relacionadas = collect();
        }

        $otrasNoticias = Noticia::with('categoria')->publicadas()
            ->whereHas('categoria')
            ->where('id', '!=', $noticia->id)
            ->latest('publicado_en')
            ->take(3)
            ->get();

        return view('noticias.show', compact('noticia', 'otrasNoticias', 'relacionadas'));
    }

    public function byTag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $noticias = Noticia::with('tags', 'categoria')
            ->publicadas()
            ->whereHas('categoria')
            ->whereHas('tags', fn ($q) => $q->where('tags.slug', $slug))
            ->latest('publicado_en')
            ->paginate(12);

        return view('noticias.tag', compact('tag', 'noticias'));
    }

    public function byCategoria(string $slug)
    {
        $categoria = Categoria::where('slug', $slug)->firstOrFail();

        $noticias = Noticia::with('tags', 'categoria')
            ->publicadas()
            ->where('categoria_id', $categoria->id)
            ->latest('publicado_en')
            ->paginate(12);

        return view('noticias.categoria', compact('categoria', 'noticias'));
    }

    public function sitemap(): Response
    {
        $noticias   = Noticia::with('categoria')->publicadas()->whereHas('categoria')->latest('publicado_en')->get(['id', 'slug', 'updated_at', 'categoria_id']);
        $tags       = Tag::whereHas('noticias', fn ($q) => $q->where('publicado', true))->get(['slug', 'updated_at']);
        $categorias = Categoria::whereHas('noticias', fn ($q) => $q->where('publicado', true))->get(['slug', 'updated_at']);

        $content = view('noticias.sitemap', compact('noticias', 'tags', 'categorias'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}
