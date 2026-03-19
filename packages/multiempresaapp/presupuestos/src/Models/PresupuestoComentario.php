<?php

namespace MultiempresaApp\Presupuestos\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PresupuestoComentario extends Model
{
    protected $table = 'presupuesto_comentarios';

    protected $fillable = [
        'presupuesto_id',
        'user_id',
        'autor_nombre',
        'contenido',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAutorAttribute(): string
    {
        return $this->usuario?->name ?? $this->autor_nombre ?? 'Cliente';
    }
}
