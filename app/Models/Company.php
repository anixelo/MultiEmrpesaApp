<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MultiempresaApp\Clientes\Models\Cliente;
use MultiempresaApp\Incidents\Models\Incident;
use MultiempresaApp\Notas\Models\Nota;
use MultiempresaApp\Plans\Models\Subscription;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
use MultiempresaApp\Servicios\Models\Servicio;

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
        'promo_plan_id',
        'promo_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'active'        => 'boolean',
            'promo_ends_at' => 'datetime',
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

    public function notas()
    {
        return $this->hasMany(Nota::class, 'empresa_id');
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

    public function canUseNotas(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return false;
        return (bool) ($plan->has_notes ?? false);
    }

    public function canUsePlantillas(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return false;
        return (bool) ($plan->has_plantillas ?? false);
    }

    public function canUseEnvioEnlace(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return false;
        return (bool) ($plan->has_envio_enlace ?? false);
    }

    public function canUseHistorialCambios(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return false;
        return (bool) ($plan->has_historial_cambios ?? false);
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

    public function isInPromo(): bool
    {
        if (!$this->promo_plan_id || !$this->promo_ends_at) {
            return false;
        }
        return $this->promo_ends_at->isFuture();
    }

    public function promoPlan(): ?\MultiempresaApp\Plans\Models\Plan
    {
        if (!$this->promo_plan_id) return null;
        return \MultiempresaApp\Plans\Models\Plan::find($this->promo_plan_id);
    }
}
