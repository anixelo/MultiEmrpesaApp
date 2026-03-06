<?php

namespace MultiempresaApp\Servicios\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use SoftDeletes;

    protected $table = 'servicios';

    protected $fillable = ['empresa_id', 'nombre', 'descripcion', 'precio', 'iva_tipo', 'activo', 'orden'];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'precio'  => 'decimal:2',
        'iva_tipo' => 'decimal:2',
        'activo'  => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    public function scopeDeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function getIvaTipoLabelAttribute(): string
    {
        if ($this->iva_tipo === null) {
            return 'Sin IVA específico';
        }

        return $this->iva_tipo . '%';
    }
}
