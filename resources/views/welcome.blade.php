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
            Para autónomos y pequeñas pymes
        </span>
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight mb-6">
            Crea y envía presupuestos<br>
            <span class="text-indigo-600">en segundos</span>
        </h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-10">
            La forma más sencilla de preparar presupuestos profesionales para tus clientes. Sin hojas de cálculo, sin Word y sin complicaciones.
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
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Todo lo que necesitas para crear presupuestos</h2>
            <p class="text-gray-600 max-w-xl mx-auto">
                Una herramienta simple pensada para autónomos y pequeñas empresas que necesitan preparar presupuestos profesionales sin perder tiempo.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">

@php
$features = [

[
    'icon' => 'M9 12h6m-6 4h6M9 8h6M7 3h10a2 2 0 012 2v14l-5-3-5 3V5a2 2 0 012-2z',
    'title' => 'Presupuestos profesionales',
    'desc' => 'Crea presupuestos claros y profesionales en segundos con cálculo automático de totales, descuentos e IVA.',
    'color' => 'blue'
],

[
    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'title' => 'Gestión de clientes',
    'desc' => 'Guarda tus clientes y reutilízalos al instante al crear nuevos presupuestos. Accede al historial completo de cada cliente.',
    'color' => 'green'
],

[
    'icon' => 'M3 7h18M3 12h18M3 17h18',
    'title' => 'Conceptos reutilizables',
    'desc' => 'Define tus servicios y productos habituales con precio e IVA para añadirlos a los presupuestos en pocos clics.',
    'color' => 'indigo'
],

[
    'icon' => 'M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0 8a3 3 0 100-6 3 3 0 000 6z',
    'title' => 'Seguimiento de presupuestos',
    'desc' => 'Consulta fácilmente qué presupuestos están enviados, vistos, aceptados o rechazados por tus clientes.',
    'color' => 'purple'
],

[
    'icon' => 'M9 17v-2a4 4 0 014-4h4',
    'title' => 'Enlace para clientes',
    'desc' => 'Envía presupuestos mediante un enlace único para que el cliente pueda verlo desde cualquier dispositivo.',
    'color' => 'yellow'
],

[
    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z',
    'title' => 'Trabajo en equipo',
    'desc' => 'Permite que varios usuarios creen y gestionen presupuestos dentro de la misma empresa. Ideal para equipos pequeños.',
    'color' => 'pink'
],

[
    'icon' => 'M4 7h16M4 11h16M4 15h16M4 19h16',
    'title' => 'Multiempresa',
    'desc' => 'Gestiona presupuestos para varias empresas desde una única cuenta. Perfecto para autónomos que trabajan con diferentes negocios.',
    'color' => 'teal'
],

[
    'icon' => 'M12 8v8m0 0l3-3m-3 3l-3-3M4 4h16v16H4z',
    'title' => 'Descarga en PDF',
    'desc' => 'Descarga tus presupuestos en PDF con un diseño limpio y profesional listo para enviar o imprimir.',
    'color' => 'red'
],

[
    'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16h6M21 12c0 4.418-4.03 8-9 8a9.77 9.77 0 01-4-.8L3 20l1.8-3.2A7.94 7.94 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
    'title' => 'Zona de incidencias',
    'desc' => 'Registra rápidamente incidencias, dudas y comentarios en la plataforma.',
    'color' => 'orange'
],

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

        @php
        $promoSettings = [
            'plan_id' => \App\Models\AppSetting::get('promo_plan_id'),
            'months'  => (int) \App\Models\AppSetting::get('promo_months', 0),
        ];
        $promoPlan = $promoSettings['plan_id'] ? \MultiempresaApp\Plans\Models\Plan::find($promoSettings['plan_id']) : null;
        @endphp

        {{-- Promo block --}}
        @if($promoPlan && $promoSettings['months'] > 0)
        <div class="mb-12 relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 shadow-xl text-white">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            </div>
            <div class="relative flex flex-col sm:flex-row items-center gap-6">
                <div class="flex-1 text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold mb-3">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/></svg>
                        Oferta de lanzamiento
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold mb-2">
                        {{ $promoSettings['months'] }} {{ $promoSettings['months'] == 1 ? 'mes' : 'meses' }} gratis con el plan <span class="text-yellow-300">{{ $promoPlan->name }}</span>
                    </h2>
                    <p class="text-indigo-100 max-w-lg">
                        Regístrate ahora y disfruta de {{ $promoSettings['months'] }} {{ $promoSettings['months'] == 1 ? 'mes' : 'meses' }} del plan {{ $promoPlan->name }} completamente gratis. Sin tarjeta de crédito. Sin compromisos.
                    </p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-7 py-3.5 rounded-xl hover:bg-indigo-50 transition shadow-lg">
                        Comienza gratis →
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Planes y Precios</h2>
            <p class="text-gray-600 max-w-xl mx-auto">Elige el plan que mejor se adapta a tu forma de trabajar y empieza a crear presupuestos en minutos.</p>
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
                        {{ ($plan->max_presupuestos ?? 0) == 0 ? 'Presupuestos ilimitados' : 'Hasta ' . $plan->max_presupuestos . ' presupuestos/mes' }}
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        {{ ($plan->max_empresas ?? 0) == 0 ? 'Empresas ilimitadas' : 'Hasta ' . $plan->max_empresas . ' empresas' }}
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

{{-- Noticias --}}
@php
$noticias = \MultiempresaApp\Noticias\Models\Noticia::publicadas()
    ->latest('publicado_en')
    ->take(6)
    ->get();
@endphp

@if($noticias->isNotEmpty())
<section id="noticias" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Últimas noticias
            </span>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Novedades y actualizaciones</h2>
            <p class="text-gray-600 max-w-xl mx-auto">Mantente al día con las últimas noticias sobre la plataforma</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($noticias as $noticia)
            <a href="{{ route('noticias.show', $noticia->slug) }}"
               class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all hover:-translate-y-0.5">
                @if($noticia->imagen)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ Storage::url($noticia->imagen) }}" alt="{{ $noticia->titulo }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                @else
                <div class="aspect-video bg-gradient-to-br from-indigo-50 via-purple-50 to-blue-50 flex items-center justify-center">
                    <svg class="w-12 h-12 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                @endif
                <div class="p-5">
                    @if($noticia->publicado_en)
                    <time class="text-xs text-indigo-500 font-medium mb-2 block">
                        {{ $noticia->publicado_en->format('d \d\e F \d\e Y') }}
                    </time>
                    @endif
                    <h3 class="font-semibold text-gray-900 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors mb-2">
                        {{ $noticia->titulo }}
                    </h3>
                    @if($noticia->meta_description)
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $noticia->meta_description }}</p>
                    @else
                    <p class="text-sm text-gray-500 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 100) }}</p>
                    @endif
                    <span class="inline-flex items-center gap-1 text-xs text-indigo-600 font-medium mt-3">
                        Leer más
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-24 bg-indigo-600">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">Empieza a enviar presupuestos hoy mismo</h2>
        <p class="text-indigo-200 mb-8 text-lg">Crea tu cuenta gratuita y empieza a enviar presupuestos profesionales a tus clientes en minutos.</p>
        <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg">
            Crear cuenta gratis →
        </a>
    </div>
</section>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
        © {{ date('Y') }} {{ config('app.name') }} — Anixelo.com
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
