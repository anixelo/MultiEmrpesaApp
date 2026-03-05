<?php

namespace MultiempresaApp\Incidents;

use Illuminate\Support\ServiceProvider;

class IncidentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
