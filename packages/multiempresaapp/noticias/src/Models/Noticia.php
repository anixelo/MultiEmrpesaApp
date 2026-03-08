<?php

namespace MultiempresaApp\Noticias\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Noticia extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
        'contenido',
        'imagen',
        'meta_description',
        'publicado',
        'publicado_en',
    ];

    protected function casts(): array
    {
        return [
            'publicado'    => 'boolean',
            'publicado_en' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($noticia) {
            if (empty($noticia->slug)) {
                $noticia->slug = static::uniqueSlug($noticia->titulo);
            }
        });

        static::updating(function ($noticia) {
            if ($noticia->isDirty('titulo') && empty($noticia->getOriginal('slug'))) {
                $noticia->slug = static::uniqueSlug($noticia->titulo);
            }
        });
    }

    public static function uniqueSlugPublic(string $titulo): string
    {
        return static::uniqueSlug($titulo);
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

    public function scopePublicadas($query)
    {
        return $query->where('publicado', true);
    }
}
