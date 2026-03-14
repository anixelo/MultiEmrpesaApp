<?php

namespace MultiempresaApp\SimpleAnalytics\Models;

use App\Models\User;
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
        'user_id',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
