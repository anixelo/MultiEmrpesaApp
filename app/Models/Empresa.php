<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'name', 'nif', 'email', 'phone', 'address', 'logo', 'active',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? \Storage::url($this->logo) : null;
    }

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function cuenta()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function presupuestos()
    {
        return $this->hasMany(\MultiempresaApp\Presupuestos\Models\Presupuesto::class, 'negocio_id');
    }
}
