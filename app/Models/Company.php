<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MultiempresaApp\Clientes\Models\Cliente;
use MultiempresaApp\Incidents\Models\Incident;
use MultiempresaApp\Plans\Models\Subscription;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
use MultiempresaApp\Servicios\Models\Servicio;
use MultiempresaApp\Tasks\Models\Task;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
            }
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'empresa_id')->latest();
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'empresa_id');
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class, 'empresa_id');
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class, 'empresa_id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class, 'empresa_id');
    }

    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class, 'empresa_id');
    }

    public function empresas()
    {
        return $this->hasMany(Empresa::class, 'company_id');
    }

    public function activePlan()
    {
        return $this->subscription?->plan;
    }

    public function canAddUser(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return true;
        return $this->users()->count() < $plan->max_users;
    }

    public function canCreateIncident(): bool
    {
        return true;
    }

    public function canCreatePresupuesto(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return true;
        $max = $plan->max_presupuestos ?? 0;
        if ($max === 0) return true;
        $count = $this->presupuestos()
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        return $count < $max;
    }

    public function canUseTasks(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return false;
        return (bool) $plan->has_tasks;
    }

    public function canCreateEmpresa(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return true;
        $max = $plan->max_empresas ?? 0;
        if ($max === 0) return true;
        $count = $this->empresas()->where('active', true)->count();
        return $count < $max;
    }
}
