<?php

namespace MultiempresaApp\Noticias\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = ['nombre', 'slug'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Tag $tag) {
            if (empty($tag->slug)) {
                $tag->slug = static::uniqueSlug($tag->nombre);
            }
        });
    }

    protected static function uniqueSlug(string $nombre): string
    {
        $slug = Str::slug($nombre);
        $base = $slug;
        $count = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }
        return $slug;
    }

    public function noticias(): BelongsToMany
    {
        return $this->belongsToMany(Noticia::class, 'noticia_tag');
    }
}
