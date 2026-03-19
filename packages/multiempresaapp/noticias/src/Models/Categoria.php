<?php

namespace MultiempresaApp\Noticias\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Categoria extends Model
{
    protected $fillable = ['titulo', 'slug', 'contenido', 'imagen'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Categoria $categoria) {
            if (empty($categoria->slug)) {
                $categoria->slug = static::uniqueSlug($categoria->titulo);
            }
        });
    }

    protected static function uniqueSlug(string $titulo): string
    {
        $slug = Str::slug($titulo);
        $base = $slug;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }
        return $slug;
    }

    public static function uniqueSlugPublic(string $titulo): string
    {
        return static::uniqueSlug($titulo);
    }

    public function noticias(): HasMany
    {
        return $this->hasMany(Noticia::class);
    }
}
