<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'MultiEmpresaApp') }} — Gestión Multi-Empresa</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased bg-white text-gray-900">

{{-- Navigation --}}
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="/" class="flex items-center gap-2 text-indigo-600 font-bold text-xl">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                {{ config('app.name') }}
            </a>
            <nav class="hidden md:flex items-center gap-6">
                <a href="#features" class="text-sm text-gray-600 hover:text-indigo-600 transition">Funcionalidades</a>
                <a href="#pricing" class="text-sm text-gray-600 hover:text-indigo-600 transition">Precios</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                        Registrarse
                    </a>
                @endauth
            </nav>
            @auth
            <a href="{{ route('dashboard') }}" class="md:hidden bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="md:hidden bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Entrar</a>
            @endauth
        </div>
    </div>
</header>

{{-- Hero --}}
<section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-24 overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-100 rounded-full opacity-40 blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/></svg>
            Sistema Multi-Empresa todo en uno
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
            Gestiona tu empresa<br>
            <span class="text-indigo-600">de forma inteligente</span>
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
            Plataforma completa para gestionar empresas, incidencias, equipos y suscripciones con notificaciones en tiempo real y soporte PWA.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @auth
            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Ir al Dashboard →
            </a>
            @else
            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Empezar gratis →
            </a>
            <a href="{{ route('login') }}" class="bg-white text-gray-700 border border-gray-200 px-8 py-3.5 rounded-xl font-semibold hover:bg-gray-50 transition">
                Iniciar sesión
            </a>
            @endauth
        </div>
    </div>
</section>

{{-- Features --}}
<section id="features" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Todo lo que necesitas</h2>
            <p class="text-gray-600 max-w-xl mx-auto">Una plataforma completa para gestionar cada aspecto de tu empresa</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
            $features = [
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Gestión de Incidencias', 'desc' => 'Sistema de tickets con estados, prioridades, comentarios en tiempo real y notificaciones automáticas.', 'color' => 'blue'],
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Planes y Suscripciones', 'desc' => 'Planes flexibles desde gratuito hasta empresarial. Gestiona suscripciones con Stripe integrado.', 'color' => 'indigo'],
                ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'title' => 'Notificaciones', 'desc' => 'Notificaciones internas y por email para incidencias, comentarios, cambios de plan y más.', 'color' => 'purple'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Multi-Empresa', 'desc' => 'Gestión centralizada de múltiples empresas, usuarios y roles desde un único panel de control.', 'color' => 'green'],
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Gestión de Tareas', 'desc' => 'Asignación y seguimiento de tareas con prioridades, fechas límite y estados actualizables.', 'color' => 'yellow'],
                ['icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z', 'title' => 'App Instalable (PWA)', 'desc' => 'Instala la app en cualquier dispositivo. Funciona offline con caché inteligente de rutas y recursos.', 'color' => 'pink'],
            ];
            @endphp

            @foreach($features as $feature)
            <div class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-lg transition-shadow group">
                <div class="w-12 h-12 bg-{{ $feature['color'] }}-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-{{ $feature['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Pricing --}}
<section id="pricing" class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Planes y Precios</h2>
            <p class="text-gray-600 max-w-xl mx-auto">Elige el plan que mejor se adapta a las necesidades de tu empresa</p>
        </div>

        @php
        $plans = \MultiempresaApp\Plans\Models\Plan::active()->orderBy('price_monthly')->get();
        @endphp

        @if($plans->isNotEmpty())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach($plans as $plan)
            @php $isPro = $loop->iteration === 2 && $plans->count() > 1; @endphp
            <div class="relative bg-white rounded-2xl border {{ $isPro ? 'border-indigo-500 shadow-xl shadow-indigo-100' : 'border-gray-200 shadow-sm' }} p-8 flex flex-col">
                @if($isPro)
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow">Más popular</span>
                </div>
                @endif

                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4">{{ $plan->description }}</p>
                    <div class="flex items-baseline gap-1">
                        @if($plan->isFree())
                            <span class="text-4xl font-bold text-gray-900">Gratis</span>
                        @else
                            <span class="text-4xl font-bold text-gray-900">€{{ number_format($plan->price_monthly, 2) }}</span>
                            <span class="text-sm text-gray-500">/mes</span>
                        @endif
                    </div>
                    @if(!$plan->isFree() && $plan->price_yearly > 0)
                    <p class="text-xs text-green-600 mt-1">
                        O €{{ number_format($plan->price_yearly, 2) }}/año (ahorra {{ round((1 - ($plan->price_yearly / ($plan->price_monthly * 12))) * 100) }}%)
                    </p>
                    @endif
                </div>

                <ul class="space-y-3 mb-8 flex-1">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Hasta {{ $plan->max_users >= 999 ? 'ilimitados' : $plan->max_users }} usuarios
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Hasta {{ $plan->max_incidents >= 999 ? 'ilimitadas' : $plan->max_incidents }} incidencias activas
                    </li>
                    @if($plan->features)
                        @foreach($plan->features as $feature)
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    @endif
                </ul>

                <a href="{{ route('register') }}"
                   class="block text-center py-3 px-6 rounded-xl font-semibold transition
                          {{ $isPro ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    {{ $plan->isFree() ? 'Empezar gratis' : 'Elegir plan' }}
                </a>
            </div>
            @endforeach
        </div>
        @else
        {{-- Fallback if no plans in DB --}}
        <div class="grid sm:grid-cols-3 gap-8 max-w-5xl mx-auto">
            @foreach([
                ['name'=>'Gratuito','price'=>'Gratis','features'=>['3 usuarios','5 incidencias','Soporte email'],'highlight'=>false],
                ['name'=>'Profesional','price'=>'€29.99/mes','features'=>['20 usuarios','50 incidencias','Soporte prioritario','Informes avanzados'],'highlight'=>true],
                ['name'=>'Empresarial','price'=>'€99.99/mes','features'=>['Usuarios ilimitados','Incidencias ilimitadas','Soporte 24/7','API access','SSO'],'highlight'=>false],
            ] as $plan)
            <div class="bg-white rounded-2xl border {{ $plan['highlight'] ? 'border-indigo-500 shadow-xl' : 'border-gray-200' }} p-8">
                <h3 class="text-xl font-bold mb-2">{{ $plan['name'] }}</h3>
                <div class="text-3xl font-bold text-gray-900 mb-6">{{ $plan['price'] }}</div>
                <ul class="space-y-3 mb-8">
                    @foreach($plan['features'] as $f)
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ $f }}
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('register') }}" class="block text-center py-3 rounded-xl font-semibold {{ $plan['highlight'] ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                    Empezar
                </a>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- CTA --}}
<section class="py-24 bg-indigo-600">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">¿Listo para empezar?</h2>
        <p class="text-indigo-200 mb-8 text-lg">Crea tu cuenta gratuita y empieza a gestionar tu empresa de forma inteligente hoy mismo.</p>
        <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg">
            Crear cuenta gratis →
        </a>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ config('app.name') }} — Sistema Multi-Empresa
    </div>
</footer>

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch(() => {});
    });
}
</script>
</body>
</html>
