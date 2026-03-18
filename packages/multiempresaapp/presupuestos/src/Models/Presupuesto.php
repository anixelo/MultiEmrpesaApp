<?php

namespace MultiempresaApp\Presupuestos\Models;

use App\Models\Company;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use MultiempresaApp\Clientes\Models\Cliente;

class Presupuesto extends Model
{
    use SoftDeletes;

    protected $table = 'presupuestos';

    protected $fillable = [
        'empresa_id', 'negocio_id', 'cliente_id', 'numero', 'fecha', 'estado',
        'subtotal_bruto', 'subtotal_descuentos', 'total_base_imponible', 'total_iva', 'total',
        'notas', 'validez_hasta', 'forma_pago', 'observaciones', 'token_publico',
        'enviado_en', 'visto_en', 'aceptado_en', 'rechazado_en', 'created_by',
        'pendiente_revision_en', 'validado_en', 'nota_revision', 'revisado_por',
    ];

    protected function casts(): array
    {
        return [
            'fecha'                   => 'date',
            'validez_hasta'           => 'date',
            'enviado_en'              => 'datetime',
            'visto_en'                => 'datetime',
            'aceptado_en'             => 'datetime',
            'rechazado_en'            => 'datetime',
            'pendiente_revision_en'   => 'datetime',
            'validado_en'             => 'datetime',
            'subtotal_bruto'          => 'decimal:2',
            'subtotal_descuentos'     => 'decimal:2',
            'total_base_imponible'    => 'decimal:2',
            'total_iva'               => 'decimal:2',
            'total'                   => 'decimal:2',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($presupuesto) {
            if (empty($presupuesto->token_publico)) {
                $presupuesto->token_publico = Str::random(32);
            }
        });
    }

    public function empresa()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    public function negocio()
    {
        return $this->belongsTo(Empresa::class, 'negocio_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function lineas()
    {
        return $this->hasMany(PresupuestoLinea::class)->orderBy('orden');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function audits()
    {
        return $this->hasMany(PresupuestoAudit::class)->orderBy('created_at', 'desc');
    }

    public function scopeDeEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'borrador'            => 'Borrador',
            'pendiente_revision'  => 'Pendiente de revisión',
            'validado'            => 'Validado',
            'enviado'             => 'Enviado',
            'visto'               => 'Visto',
            'aceptado'            => 'Aceptado',
            'rechazado'           => 'Rechazado',
            default               => ucfirst($this->estado),
        };
    }

    public function getEstadoColorAttribute(): string
    {
        return match ($this->estado) {
            'borrador'            => 'gray',
            'pendiente_revision'  => 'orange',
            'validado'            => 'teal',
            'enviado'             => 'blue',
            'visto'               => 'purple',
            'aceptado'            => 'green',
            'rechazado'           => 'red',
            default               => 'gray',
        };
    }
}
