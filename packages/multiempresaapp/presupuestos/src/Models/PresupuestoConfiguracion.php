<?php

namespace MultiempresaApp\Presupuestos\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class PresupuestoConfiguracion extends Model
{
    protected $table = 'presupuestos_configuracion';

    protected $fillable = [
        'empresa_id', 'iva_defecto', 'prefijo', 'siguiente_numero',
        'validez_dias', 'forma_pago_defecto', 'observaciones_defecto',
    ];

    protected function casts(): array
    {
        return ['iva_defecto' => 'decimal:2'];
    }

    public function scopeDeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public static function getOrCreateForEmpresa(int $empresaId): static
    {
        return static::firstOrCreate(
            ['empresa_id' => $empresaId],
            ['iva_defecto' => 21, 'prefijo' => 'PRE', 'siguiente_numero' => 1, 'validez_dias' => 30]
        );
    }

    public function empresa()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }
}
