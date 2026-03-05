<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id', 'user_id', 'title', 'description', 'status', 'priority',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'empresa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(IncidentComment::class)->orderBy('created_at');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'open' => 'Abierta',
            'in_review' => 'En Revisión',
            'in_progress' => 'En Progreso',
            'resolved' => 'Resuelta',
            'closed' => 'Cerrada',
            default => 'Desconocido',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'open' => 'blue',
            'in_review' => 'yellow',
            'in_progress' => 'orange',
            'resolved' => 'green',
            'closed' => 'gray',
            default => 'gray',
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
            default => 'Media',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'baja' => 'gray',
            'media' => 'blue',
            'alta' => 'orange',
            'urgente' => 'red',
            default => 'gray',
        };
    }
}
