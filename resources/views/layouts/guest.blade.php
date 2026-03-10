<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MultiEmpresaApp') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased text-gray-900">

        {{-- Navigation --}}
        <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
<a href="/" class="flex items-center gap-3 text-indigo-600 font-extrabold text-xl" aria-label="Ir a inicio">
    <img 
        src="/pwa-icons/icon-192x192.png" 
        alt="Logo {{ config('app.name') }}" 
        class="w-8 h-8 shrink-0"
    >
    <span>{{ config('app.name') }}</span>
</a>
                    <nav class="flex items-center gap-4">
                        @unless (request()->routeIs('login'))
                            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">
                                Iniciar sesión
                            </a>
                        @endunless
                        @unless (request()->routeIs('register'))
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                Registrarse
                            </a>
                        @endunless
                    </nav>
                </div>
            </div>
        </header>

        {{-- Page --}}
        <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 flex flex-col justify-center py-12 relative overflow-hidden">

            {{-- Decorative blur circles --}}
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-100 rounded-full opacity-40 blur-3xl pointer-events-none"></div>

            <div class="relative sm:mx-auto sm:w-full sm:max-w-md px-4">

                {{-- Brand logo --}}
                <div class="flex justify-center mb-8">
                    <a href="/" class="flex items-center gap-2 text-indigo-600 font-bold text-2xl">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ config('app.name') }}
                    </a>
                </div>

                {{-- Form card --}}
                <div class="bg-white rounded-2xl shadow-xl shadow-indigo-100/50 border border-gray-100 px-8 py-8">
                    {{ $slot }}
                </div>

                {{-- Footer links --}}
                <div class="mt-6 text-center">
                    <nav class="flex flex-wrap items-center justify-center gap-3 text-xs text-gray-400">
                        <a href="{{ route('pages.privacy') }}" class="hover:text-indigo-600 transition">Política de Privacidad</a>
                        <span>·</span>
                        <a href="{{ route('pages.terms') }}" class="hover:text-indigo-600 transition">Términos y Condiciones</a>
                        <span>·</span>
                        <a href="{{ route('pages.contact') }}" class="hover:text-indigo-600 transition">Contacto</a>
                    </nav>
                </div>

            </div>
        </div>

    </body>
</html>
