<?php

namespace MultiempresaApp\Notas\Models;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MultiempresaApp\Clientes\Models\Cliente;
use MultiempresaApp\Presupuestos\Models\Presupuesto;

class Nota extends Model
{
    use SoftDeletes;

    protected $table = 'notas';

    protected $fillable = [
        'empresa_id',
        'cliente_id',
        'presupuesto_id',
        'titulo',
        'contenido',
        'created_by',
    ];

    public function empresa()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeDeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }
}
