<?php

namespace MultiempresaApp\Plans\Providers;

use Illuminate\Support\ServiceProvider;

class PlansServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'plans');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'plans');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
