<?php

namespace MultiempresaApp\Presupuestos\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PresupuestoAudit extends Model
{
    public $timestamps = false;

    protected $table = 'presupuesto_audits';

    protected $fillable = [
        'presupuesto_id',
        'user_id',
        'accion',
        'descripcion',
        'datos',
    ];

    protected function casts(): array
    {
        return [
            'datos'      => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAccionLabelAttribute(): string
    {
        return match ($this->accion) {
            'creado'              => 'Creado',
            'editado'             => 'Editado',
            'enviado'             => 'Marcado como enviado',
            'visto'               => 'Visto por el cliente',
            'aceptado'            => 'Aceptado por el cliente',
            'rechazado'           => 'Rechazado por el cliente',
            'duplicado'           => 'Duplicado',
            'email_enviado'       => 'Email enviado',
            'nota_aplicada'       => 'Nota aplicada',
            'plantilla_aplicada'  => 'Plantilla aplicada',
            'pendiente_revision'  => 'Revisión solicitada',
            'validado'            => 'Validado por administrador',
            'rechazado_revision'  => 'Revisión rechazada',
            'vuelto_borrador'     => 'Vuelto a borrador',
            default               => ucfirst($this->accion),
        };
    }

    public function getAccionColorAttribute(): string
    {
        return match ($this->accion) {
            'creado'              => 'gray',
            'editado'             => 'yellow',
            'enviado'             => 'blue',
            'visto'               => 'purple',
            'aceptado'            => 'green',
            'rechazado'           => 'red',
            'duplicado'           => 'gray',
            'email_enviado'       => 'blue',
            'nota_aplicada'       => 'yellow',
            'plantilla_aplicada'  => 'purple',
            'pendiente_revision'  => 'orange',
            'validado'            => 'teal',
            'rechazado_revision'  => 'red',
            'vuelto_borrador'     => 'gray',
            default               => 'gray',
        };
    }
}
