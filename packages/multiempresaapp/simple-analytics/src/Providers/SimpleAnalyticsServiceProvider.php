<?php

namespace MultiempresaApp\SimpleAnalytics\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use MultiempresaApp\SimpleAnalytics\Http\Middleware\TrackPageVisit;
use MultiempresaApp\SimpleAnalytics\Services\AnalyticsService;

class SimpleAnalyticsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/simple-analytics.php',
            'simple-analytics'
        );

        $this->app->singleton(AnalyticsService::class, fn () => new AnalyticsService());
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'simple-analytics');

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/simple-analytics.php' => config_path('simple-analytics.php'),
        ], 'simple-analytics-config');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'simple-analytics-migrations');

        // Register middleware alias so it can be used manually on route groups
        $router = $this->app['router'];
        $router->aliasMiddleware(
            config('simple-analytics.middleware_alias', 'track.visits'),
            TrackPageVisit::class
        );

        // Append TrackPageVisit to the web middleware group automatically
        $router->pushMiddlewareToGroup('web', TrackPageVisit::class);

        // Default Gate — allows superadministrador role (overridable by the app)
        Gate::define('viewAnalytics', function ($user = null) {
            return $user && $user->hasRole('superadministrador');
        });
    }
}
