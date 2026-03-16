<?php

namespace MultiempresaApp\PlantillasPresupuesto\Models;

use App\Models\Company;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PlantillaPresupuesto extends Model
{
    protected $table = 'plantillas_presupuesto';

    protected $fillable = [
        'empresa_id',
        'negocio_id',
        'nombre',
        'forma_pago',
        'observaciones',
        'created_by',
    ];

    public function empresa()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    public function negocio()
    {
        return $this->belongsTo(Empresa::class, 'negocio_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lineas()
    {
        return $this->hasMany(PlantillaPresupuestoLinea::class, 'plantilla_id')->orderBy('orden');
    }

    public function scopeDeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }
}
