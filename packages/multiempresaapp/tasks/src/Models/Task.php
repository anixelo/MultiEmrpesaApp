<?php

namespace MultiempresaApp\Tasks\Models;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'assigned_to',
        'created_by',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pendiente'   => 'yellow',
            'en_progreso' => 'blue',
            'completada'  => 'green',
            'cancelada'   => 'red',
            default       => 'gray',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            'baja'    => 'gray',
            'media'   => 'blue',
            'alta'    => 'orange',
            'urgente' => 'red',
            default   => 'gray',
        };
    }
}
