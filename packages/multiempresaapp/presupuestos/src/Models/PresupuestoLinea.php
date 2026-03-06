<?php

namespace MultiempresaApp\Presupuestos\Models;

use Illuminate\Database\Eloquent\Model;
use MultiempresaApp\Servicios\Models\Servicio;

class PresupuestoLinea extends Model
{
    protected $table = 'presupuesto_lineas';

    protected $fillable = [
        'presupuesto_id', 'servicio_id', 'orden', 'concepto', 'cantidad',
        'precio_unitario', 'descuento_tipo', 'descuento_valor',
        'base_imponible', 'iva_tipo', 'iva_cuota', 'total',
    ];

    protected function casts(): array
    {
        return [
            'cantidad'        => 'decimal:2',
            'precio_unitario' => 'decimal:2',
            'descuento_valor' => 'decimal:2',
            'base_imponible'  => 'decimal:2',
            'iva_tipo'        => 'decimal:2',
            'iva_cuota'       => 'decimal:2',
            'total'           => 'decimal:2',
        ];
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }
}
