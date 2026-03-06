<?php

namespace MultiempresaApp\Clientes\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = ['empresa_id', 'created_by', 'nombre', 'email', 'telefono', 'notas'];

    protected $dates = ['deleted_at'];

    public function company()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    public function scopeDeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }
}
