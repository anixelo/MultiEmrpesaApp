<?php

namespace MultiempresaApp\Incidents\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentComment extends Model
{
    use HasFactory;

    protected $fillable = ['incident_id', 'user_id', 'comment'];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
