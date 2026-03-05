<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        $plan = $this->subscription?->plan;
        if (!$plan) return true;
        return $this->incidents()->whereIn('status', ['open', 'in_review', 'in_progress'])->count() < $plan->max_incidents;
    }

    public function canUseTasks(): bool
    {
        $plan = $this->subscription?->plan;
        if (!$plan) return false;
        return (bool) $plan->has_tasks;
    }
}
