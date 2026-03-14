<?php

namespace MultiempresaApp\SimpleAnalytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MultiempresaApp\SimpleAnalytics\Services\AnalyticsService;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    public function __construct(private AnalyticsService $analytics) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request)) {
            $this->analytics->track($request);
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        if (! config('simple-analytics.enabled', true)) {
            return false;
        }

        // Only GET requests
        if (! $request->isMethod('GET')) {
            return false;
        }

        // Skip super-administrators
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return false;
        }

        // Skip when someone is impersonating another user
        if (session()->has('impersonating_original_id')) {
            return false;
        }

        // Skip authenticated users if configured
        if (auth()->check() && ! config('simple-analytics.track_authenticated_users', true)) {
            return false;
        }

        // Skip guests if configured
        if (! auth()->check() && ! config('simple-analytics.track_guests', true)) {
            return false;
        }

        // Ignore bots
        if (config('simple-analytics.ignore_bots', true) && $this->isBot($request)) {
            return false;
        }

        // Ignore asset extensions
        if ($this->hasIgnoredExtension($request)) {
            return false;
        }

        // Ignore configured paths
        if ($this->isIgnoredPath($request)) {
            return false;
        }

        return true;
    }

    private function isBot(Request $request): bool
    {
        $userAgent = strtolower($request->userAgent() ?? '');
        $signatures = config('simple-analytics.bot_signatures', []);

        foreach ($signatures as $signature) {
            if (str_contains($userAgent, strtolower($signature))) {
                return true;
            }
        }

        return false;
    }

    private function hasIgnoredExtension(Request $request): bool
    {
        $path = $request->path();
        $extensions = config('simple-analytics.ignore_extensions', []);

        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension === '') {
            return false;
        }

        return in_array(strtolower($extension), $extensions, true);
    }

    private function isIgnoredPath(Request $request): bool
    {
        $path = $request->path();
        $ignoredPaths = config('simple-analytics.ignore_paths', []);

        foreach ($ignoredPaths as $ignoredPath) {
            if (fnmatch($ignoredPath, $path)) {
                return true;
            }
        }

        return false;
    }
}
