<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use MultiempresaApp\Noticias\Models\Categoria;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::withCount('noticias')->latest()->paginate(20);
        return view('superadmin.categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('superadmin.categorias.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'titulo'    => ['required', 'string', 'max:255'],
            'contenido' => ['nullable', 'string'],
            'imagen'    => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'titulo'    => $request->titulo,
            'slug'      => Categoria::uniqueSlugPublic($request->titulo),
            'contenido' => $request->contenido,
        ];

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $this->saveOptimizedImage($request->file('imagen'));
        }

        Categoria::create($data);

        return redirect()->route('superadmin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria): View
    {
        return view('superadmin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $request->validate([
            'titulo'    => ['required', 'string', 'max:255'],
            'contenido' => ['nullable', 'string'],
            'imagen'    => ['nullable', 'image', 'max:5120'],
        ]);

        $data = [
            'titulo'    => $request->titulo,
            'contenido' => $request->contenido,
        ];

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen) {
                Storage::disk('public')->delete($categoria->imagen);
            }
            $data['imagen'] = $this->saveOptimizedImage($request->file('imagen'));
        }

        $categoria->update($data);

        return redirect()->route('superadmin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if ($categoria->imagen) {
            Storage::disk('public')->delete($categoria->imagen);
        }
        $categoria->delete();

        return redirect()->route('superadmin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    protected function saveOptimizedImage(\Illuminate\Http\UploadedFile $file): string
    {
        $filename = 'categorias/' . Str::uuid() . '.jpg';

        Storage::disk('public')->makeDirectory('categorias');

        $sourcePath = $file->getRealPath();
        $mime       = $file->getMimeType();

        if ($mime === 'image/png') {
            $src = imagecreatefrompng($sourcePath);
            imagesavealpha($src, true);
        } elseif ($mime === 'image/webp') {
            $src = imagecreatefromwebp($sourcePath);
        } else {
            $src = imagecreatefromjpeg($sourcePath);
        }

        if (!$src) {
            $path = $file->store('categorias', 'public');
            return $path;
        }

        $origW = imagesx($src);
        $origH = imagesy($src);
        $maxW  = 1200;
        $maxH  = 800;

        if ($origW > $maxW || $origH > $maxH) {
            $ratio = min($maxW / $origW, $maxH / $origH);
            $newW  = (int) ($origW * $ratio);
            $newH  = (int) ($origH * $ratio);
            $dst   = imagecreatetruecolor($newW, $newH);

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
