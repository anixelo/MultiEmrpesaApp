<?php

namespace MultiempresaApp\Noticias\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Str;
use MultiempresaApp\Noticias\Models\Noticia;

class NoticiaController extends Controller
{
    public function show(string $slug)
    {
        $noticia = Noticia::publicadas()->where('slug', $slug)->firstOrFail();

        $otrasNoticias = Noticia::publicadas()
            ->where('id', '!=', $noticia->id)
            ->latest('publicado_en')
            ->take(3)
            ->get();

        return view('noticias.show', compact('noticia', 'otrasNoticias'));
    }
}
