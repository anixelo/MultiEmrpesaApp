<?php

namespace MultiempresaApp\Clientes;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ClientesServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'clientes');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'clientes');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        Livewire::component('clientes.cliente-selector', \MultiempresaApp\Clientes\Livewire\ClienteSelector::class);
    }
}
