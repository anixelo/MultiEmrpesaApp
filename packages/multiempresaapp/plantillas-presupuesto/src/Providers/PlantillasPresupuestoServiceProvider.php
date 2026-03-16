<?php

namespace MultiempresaApp\PlantillasPresupuesto\Providers;

use Illuminate\Support\ServiceProvider;

class PlantillasPresupuestoServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'plantillas-presupuesto');
    }
}
