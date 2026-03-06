<?php

namespace MultiempresaApp\Incidents\Providers;

use Illuminate\Support\ServiceProvider;

class IncidentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'incidents');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'incidents');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
