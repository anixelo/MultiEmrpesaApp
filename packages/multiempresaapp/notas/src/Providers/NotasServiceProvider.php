<?php

namespace MultiempresaApp\Notas\Providers;

use Illuminate\Support\ServiceProvider;

class NotasServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'notas');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
