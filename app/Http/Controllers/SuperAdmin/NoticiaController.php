<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use MultiempresaApp\Noticias\Models\Noticia;
use MultiempresaApp\Noticias\Models\Tag;

class NoticiaController extends Controller
{
    public function index(): View
    {
        $noticias = Noticia::with('tags')->latest()->paginate(20);
        return view('superadmin.noticias.index', compact('noticias'));
    }

    public function create(): View
    {
        $tags = Tag::orderBy('nombre')->get();
        return view('superadmin.noticias.create', compact('tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'titulo'           => ['required', 'string', 'max:255'],
            'contenido'        => ['required', 'string'],
            'imagen'           => ['nullable', 'image', 'max:5120'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'publicado'        => ['nullable', 'boolean'],
            'tags'             => ['nullable', 'array'],
            'tags.*'           => ['string', 'max:100'],
        ]);

        $data = [
            'titulo'           => $request->titulo,
            'slug'             => Noticia::uniqueSlugPublic($request->titulo),
            'contenido'        => $request->contenido,
            'meta_description' => $request->meta_description,
            'publicado'        => $request->boolean('publicado'),
            'publicado_en'     => $request->boolean('publicado') ? now() : null,
        ];

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $this->saveOptimizedImage($request->file('imagen'));
        }

        $noticia = Noticia::create($data);

        if ($request->filled('tags')) {
            $tagIds = $this->resolveTagIds($request->input('tags', []));
            $noticia->tags()->sync($tagIds);
        }

        return redirect()->route('superadmin.noticias.index')
            ->with('success', 'Noticia creada correctamente.');
    }

    public function edit(Noticia $noticia): View
    {
        $noticia->load('tags');
        $tags = Tag::orderBy('nombre')->get();
        return view('superadmin.noticias.edit', compact('noticia', 'tags'));
    }

    public function update(Request $request, Noticia $noticia): RedirectResponse
    {
        $request->validate([
            'titulo'           => ['required', 'string', 'max:255'],
            'contenido'        => ['required', 'string'],
            'imagen'           => ['nullable', 'image', 'max:5120'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'publicado'        => ['nullable', 'boolean'],
            'tags'             => ['nullable', 'array'],
            'tags.*'           => ['string', 'max:100'],
        ]);

        $wasPublicado = $noticia->publicado;

        $data = [
            'titulo'           => $request->titulo,
            'contenido'        => $request->contenido,
            'meta_description' => $request->meta_description,
            'publicado'        => $request->boolean('publicado'),
        ];

        if ($request->boolean('publicado') && !$wasPublicado) {
            $data['publicado_en'] = now();
        }

        if ($request->hasFile('imagen')) {
            if ($noticia->imagen) {
                Storage::disk('public')->delete($noticia->imagen);
            }
            $data['imagen'] = $this->saveOptimizedImage($request->file('imagen'));
        }

        $noticia->update($data);

        $tagIds = $this->resolveTagIds($request->input('tags', []));
        $noticia->tags()->sync($tagIds);

        return redirect()->route('superadmin.noticias.index')
            ->with('success', 'Noticia actualizada correctamente.');
    }

    public function destroy(Noticia $noticia): RedirectResponse
    {
        if ($noticia->imagen) {
            Storage::disk('public')->delete($noticia->imagen);
        }
        $noticia->delete();

        return redirect()->route('superadmin.noticias.index')
            ->with('success', 'Noticia eliminada correctamente.');
    }

    /**
     * Resolve tag names to IDs, creating new tags as needed.
     *
     * @param  array<string>  $tagNames
     * @return array<int>
     */
    protected function resolveTagIds(array $tagNames): array
    {
        return collect($tagNames)
            ->filter()
            ->map(function (string $nombre) {
                $nombre = trim($nombre);
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($nombre)],
                    ['nombre' => $nombre]
                )->id;
            })
            ->unique()
            ->values()
            ->all();
    }

    protected function saveOptimizedImage(\Illuminate\Http\UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename  = 'noticias/' . Str::uuid() . '.jpg';

        Storage::disk('public')->makeDirectory('noticias');

        $sourcePath = $file->getRealPath();
        $mime       = $file->getMimeType();

        // Load image using GD
        if ($mime === 'image/png') {
            $src = imagecreatefrompng($sourcePath);
            imagesavealpha($src, true);
        } elseif ($mime === 'image/webp') {
            $src = imagecreatefromwebp($sourcePath);
        } else {
            $src = imagecreatefromjpeg($sourcePath);
        }

        if (!$src) {
            // Fallback: just store original
            $path = $file->store('noticias', 'public');
            return $path;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);
        $maxW  = 1200;
        $maxH  = 800;

        if ($origW > $maxW || $origH > $maxH) {
            $ratio  = min($maxW / $origW, $maxH / $origH);
            $newW   = (int) ($origW * $ratio);
            $newH   = (int) ($origH * $ratio);
            $dst    = imagecreatetruecolor($newW, $newH);

            // Handle transparency for PNG
            if ($mime === 'image/png') {
                imagefill($dst, 0, 0, imagecolorallocate($dst, 255, 255, 255));
            }

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
            imagedestroy($src);
            $src = $dst;
        }

        $fullPath = storage_path('app/public/' . $filename);
        imagejpeg($src, $fullPath, 80);
        imagedestroy($src);

        return $filename;
    }
}
