<?php

namespace MultiempresaApp\Tasks\Providers;

use Illuminate\Support\ServiceProvider;

class TasksServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'tasks');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'tasks');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
