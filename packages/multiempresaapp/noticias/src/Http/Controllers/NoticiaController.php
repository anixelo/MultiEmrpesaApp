<?php

namespace MultiempresaApp\Noticias\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use MultiempresaApp\Noticias\Models\Categoria;
use MultiempresaApp\Noticias\Models\Noticia;
use MultiempresaApp\Noticias\Models\Tag;

class NoticiaController extends Controller
{
    public function show(string $slug)
    {
        $noticia = Noticia::with('tags', 'categoria')->publicadas()->where('slug', $slug)->firstOrFail();

        // Related news: same tags first, then fill with latest
        $tagIds = $noticia->tags->pluck('id');

        if ($tagIds->isNotEmpty()) {
            $relacionadas = Noticia::with('tags', 'categoria')
                ->publicadas()
                ->where('id', '!=', $noticia->id)
                ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds))
                ->latest('publicado_en')
                ->take(3)
                ->get();
        } else {
            $relacionadas = collect();
        }

        $otrasNoticias = Noticia::with('categoria')->publicadas()
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
        $noticias   = Noticia::publicadas()->latest('publicado_en')->get(['slug', 'updated_at']);
        $tags       = Tag::whereHas('noticias', fn ($q) => $q->where('publicado', true))->get(['slug', 'updated_at']);
        $categorias = Categoria::whereHas('noticias', fn ($q) => $q->where('publicado', true))->get(['slug', 'updated_at']);

        $content = view('noticias.sitemap', compact('noticias', 'tags', 'categorias'))->render();

        return response($content, 200, ['Content-Type' => 'application/xml']);
    }
}
