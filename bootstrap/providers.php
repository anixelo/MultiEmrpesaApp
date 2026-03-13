<?php

return [
    App\Providers\AppServiceProvider::class,
    MultiempresaApp\Incidents\Providers\IncidentsServiceProvider::class,
    MultiempresaApp\Notifications\Providers\NotificationsServiceProvider::class,
    MultiempresaApp\Plans\Providers\PlansServiceProvider::class,
    MultiempresaApp\Clientes\Providers\ClientesServiceProvider::class,
    MultiempresaApp\Servicios\Providers\ServiciosServiceProvider::class,
    MultiempresaApp\Presupuestos\Providers\PresupuestosServiceProvider::class,
    MultiempresaApp\Noticias\Providers\NoticiasServiceProvider::class,
    MultiempresaApp\Notas\Providers\NotasServiceProvider::class,
    MultiempresaApp\SimpleAnalytics\Providers\SimpleAnalyticsServiceProvider::class,
];
