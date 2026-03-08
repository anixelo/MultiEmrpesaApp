<?php

namespace MultiempresaApp\Noticias\Providers;

use Illuminate\Support\ServiceProvider;

class NoticiasServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'noticias');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
