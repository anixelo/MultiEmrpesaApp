<?php

namespace MultiempresaApp\SimpleAnalytics\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    protected $fillable = [
        'url',
        'path',
        'ip',
        'user_agent',
        'referer',
        'method',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];
}
