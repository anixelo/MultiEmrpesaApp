<?php

namespace MultiempresaApp\Plans\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price_monthly', 'price_yearly',
        'max_users', 'max_presupuestos', 'max_empresas', 'features', 'has_tasks', 'has_notes', 'has_plantillas', 'has_envio_enlace', 'has_historial_cambios', 'active',
    ];

    protected function casts(): array
    {
        return [
            'active'          => 'boolean',
            'has_tasks'       => 'boolean',
            'has_notes'       => 'boolean',
            'has_plantillas'  => 'boolean',
            'has_envio_enlace'    => 'boolean',
            'has_historial_cambios' => 'boolean',
            'features'        => 'array',
            'price_monthly'   => 'decimal:2',
            'price_yearly'    => 'decimal:2',
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
