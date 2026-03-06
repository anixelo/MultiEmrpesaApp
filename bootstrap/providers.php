<?php

return [
    App\Providers\AppServiceProvider::class,
    MultiempresaApp\Tasks\Providers\TasksServiceProvider::class,
    MultiempresaApp\Incidents\Providers\IncidentsServiceProvider::class,
    MultiempresaApp\Notifications\Providers\NotificationsServiceProvider::class,
    MultiempresaApp\Plans\Providers\PlansServiceProvider::class,
    MultiempresaApp\Clientes\Providers\ClientesServiceProvider::class,
    MultiempresaApp\Servicios\Providers\ServiciosServiceProvider::class,
    MultiempresaApp\Presupuestos\Providers\PresupuestosServiceProvider::class,
];
