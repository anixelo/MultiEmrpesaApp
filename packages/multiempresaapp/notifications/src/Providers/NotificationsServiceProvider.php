<?php

namespace MultiempresaApp\Notifications\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'notifications');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'notifications');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
    }
}
