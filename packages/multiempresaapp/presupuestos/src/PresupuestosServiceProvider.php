<?php

namespace MultiempresaApp\Presupuestos;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class PresupuestosServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'presupuestos');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'presupuestos');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Livewire::component('presupuestos.presupuesto-form', \MultiempresaApp\Presupuestos\Livewire\PresupuestoForm::class);
    }
}
