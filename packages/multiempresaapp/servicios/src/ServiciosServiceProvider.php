<?php

namespace MultiempresaApp\Servicios;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ServiciosServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'servicios');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'servicios');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Livewire::component('servicios.servicio-selector', \MultiempresaApp\Servicios\Livewire\ServicioSelector::class);
    }
}
