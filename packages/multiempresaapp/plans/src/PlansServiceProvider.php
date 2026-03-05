<?php

namespace MultiempresaApp\Plans;

use Illuminate\Support\ServiceProvider;

class PlansServiceProvider extends ServiceProvider
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
