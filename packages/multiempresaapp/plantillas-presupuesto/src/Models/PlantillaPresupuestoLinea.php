<?php

namespace MultiempresaApp\PlantillasPresupuesto\Models;

use Illuminate\Database\Eloquent\Model;

class PlantillaPresupuestoLinea extends Model
{
    protected $table = 'plantilla_presupuesto_lineas';

    protected $fillable = [
        'plantilla_id',
        'orden',
        'concepto',
        'cantidad',
        'precio_unitario',
        'descuento_tipo',
        'descuento_valor',
        'iva_tipo',
    ];

    protected $casts = [
        'cantidad'        => 'float',
        'precio_unitario' => 'float',
        'descuento_valor' => 'float',
        'iva_tipo'        => 'float',
    ];

    public function plantilla()
    {
        return $this->belongsTo(PlantillaPresupuesto::class, 'plantilla_id');
    }
}
