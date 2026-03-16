<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enable / Disable tracking
    |--------------------------------------------------------------------------
    */
    'enabled' => true,

    /*
    |--------------------------------------------------------------------------
    | Middleware alias registered for manual use on specific route groups
    |--------------------------------------------------------------------------
    */
    'middleware_alias' => 'track.visits',

    /*
    |--------------------------------------------------------------------------
    | Paths to ignore (supports wildcards with fnmatch)

        'login',
        'register',
        'admin/*',
        'superadmin/*',

    |--------------------------------------------------------------------------
    */
    'ignore_paths' => [

        'logout',
        'api/*',
        'up',
        '_debugbar/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | File extensions to ignore (asset files)
    |--------------------------------------------------------------------------
    */
    'ignore_extensions' => [
        'css', 'js', 'jpg', 'jpeg', 'png', 'gif',
        'svg', 'webp', 'ico', 'woff', 'woff2', 'ttf',
        'eot', 'otf', 'map', 'json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bot filtering
    |--------------------------------------------------------------------------
    */
    'ignore_bots' => true,

    'bot_signatures' => [
        'bot', 'crawler', 'spider', 'slurp', 'curl',
        'wget', 'scrapy', 'python-requests', 'go-http-client',
        'facebookexternalhit', 'linkedinbot', 'twitterbot',
    ],

    /*
    |--------------------------------------------------------------------------
    | Track authenticated / guest users
    |--------------------------------------------------------------------------
    */
    'track_guests'               => true,
    'track_authenticated_users'  => true,

    /*
    |--------------------------------------------------------------------------
    | Admin panel configuration
    |--------------------------------------------------------------------------
    */
    'admin_route_prefix' => 'superadmin/analytics',
    'admin_middleware'   => ['web', 'auth', 'role:superadministrador'],
    'admin_gate'         => 'viewAnalytics',
];
