<?php

namespace MultiempresaApp\Plans\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price_monthly', 'price_yearly',
        'max_users', 'max_presupuestos', 'features', 'has_tasks', 'active',
    ];

    protected function casts(): array
    {
        return [
            'active'        => 'boolean',
            'has_tasks'     => 'boolean',
            'features'      => 'array',
            'price_monthly' => 'decimal:2',
            'price_yearly'  => 'decimal:2',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function isFree(): bool
    {
        return $this->price_monthly == 0;
    }
}
