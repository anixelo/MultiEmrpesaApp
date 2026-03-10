<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $appName = config('app.name', 'Mis presupuestos');
        $appUrl = url('/');
        $appDescription = 'Software de presupuestos para autónomos y pymes. Crea presupuestos online, envíalos a tus clientes y descárgalos en PDF en segundos.';
        $appTitle = $appName . ' — Software de presupuestos para autónomos y pymes';
        $appImage = asset('icons/icon-512x512.png');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $appTitle }}</title>
    <meta name="description" content="{{ $appDescription }}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{{ $appUrl }}">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $appTitle }}">
    <meta property="og:description" content="{{ $appDescription }}">
    <meta property="og:url" content="{{ $appUrl }}">
    <meta property="og:image" content="{{ $appImage }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $appTitle }}">
    <meta name="twitter:description" content="{{ $appDescription }}">
    <meta name="twitter:image" content="{{ $appImage }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Structured data: SoftwareApplication --}}
    <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@type":"SoftwareApplication",
      "name":"{{ $appName }}",
      "applicationCategory":"BusinessApplication",
      "operatingSystem":"Web",
      "url":"{{ $appUrl }}",
      "description":"{{ $appDescription }}",
      "offers":{
        "@type":"Offer",
        "price":"0",
        "priceCurrency":"EUR"
      },
      "creator":{
        "@type":"Organization",
        "name":"Anixelo",
        "url":"https://anixelo.com"
      }
    }
    </script>

    {{-- Structured data: FAQ --}}
    <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@type":"FAQPage",
      "mainEntity":[
        {
          "@type":"Question",
          "name":"¿Qué es Mis presupuestos?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Mis presupuestos es un software de presupuestos online para autónomos y pequeñas empresas que permite crear, enviar y descargar presupuestos profesionales en PDF."
          }
        },
        {
          "@type":"Question",
          "name":"¿Puedo usarlo gratis?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. La plataforma dispone de un plan gratuito para empezar a crear presupuestos de forma sencilla."
          }
        },
        {
          "@type":"Question",
          "name":"¿Puedo descargar los presupuestos en PDF?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. Puedes descargar tus presupuestos en PDF con un formato limpio y profesional listo para enviar o imprimir."
          }
        },
        {
          "@type":"Question",
          "name":"¿Sirve para varias empresas?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. Mis presupuestos permite gestionar varias empresas desde una misma cuenta según el plan contratado."
          }
        }
      ]
    }
    </script>
</head>
<body class="font-sans antialiased bg-white text-gray-900 selection:bg-indigo-100 selection:text-indigo-900">

<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-white border border-gray-200 rounded-lg px-4 py-2 text-sm z-[100]">
    Saltar al contenido
</a>

{{-- Navigation --}}
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
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

            <nav class="hidden md:flex items-center gap-6" aria-label="Navegación principal">
                <a href="#features" class="text-sm text-gray-600 hover:text-indigo-600 transition">Funcionalidades</a>
                <a href="#how-it-works" class="text-sm text-gray-600 hover:text-indigo-600 transition">Cómo funciona</a>
                <a href="#pricing" class="text-sm text-gray-600 hover:text-indigo-600 transition">Precios</a>
                <a href="#faq" class="text-sm text-gray-600 hover:text-indigo-600 transition">Preguntas frecuentes</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Ir al panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Registrarse
                    </a>
                @endauth
            </nav>

            @auth
                <a href="{{ route('dashboard') }}" class="md:hidden bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Panel</a>
            @else
                <div class="md:hidden flex items-center gap-2">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 px-3 py-2 rounded-lg border border-gray-200">Entrar</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Registro</a>
                </div>
            @endauth
        </div>
    </div>
</header>

<main id="main-content">

    {{-- Hero --}}
    <section class="relative bg-gradient-to-br from-indigo-50 via-white to-purple-50 py-20 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-100 rounded-full opacity-40 blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-purple-100 rounded-full opacity-40 blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto text-center">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-6">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                    </svg>
                    Para autónomos y pequeñas pymes
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Software de presupuestos para autónomos y pymes
                </h1>

                <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto mb-10">
                    Crea presupuestos online en segundos, envíalos a tus clientes con un enlace único y descárgalos en PDF sin usar Excel, Word ni plantillas complicadas.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Ir al panel →
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

                <div class="flex flex-wrap justify-center gap-3 text-sm text-gray-500">
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Sin tarjeta de crédito</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">PDF profesional</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Multiempresa</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Trabajo en equipo</span>
                </div>
            </div>

            {{-- Preview block --}}
            <div class="mt-16 max-w-5xl mx-auto">
                <div class="rounded-3xl border border-gray-200 bg-white shadow-2xl shadow-indigo-100/50 overflow-hidden">
                    <div class="flex items-center gap-2 px-5 py-4 border-b border-gray-100 bg-gray-50">
                        <span class="w-3 h-3 rounded-full bg-red-300"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-300"></span>
                        <span class="w-3 h-3 rounded-full bg-green-300"></span>
                        <span class="ml-3 text-sm text-gray-500">Vista previa de la plataforma</span>
                    </div>
                    <div class="grid lg:grid-cols-2">
                        <div class="p-8 border-b lg:border-b-0 lg:border-r border-gray-100">
                            <p class="text-sm font-semibold text-indigo-600 mb-3">Editor rápido</p>
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">Crea tu presupuesto en menos de un minuto</h2>
                            <p class="text-gray-600 mb-6">
                                Añade cliente, conceptos, impuestos y totales desde una interfaz clara y rápida. Todo preparado para ahorrar tiempo en tu día a día.
                            </p>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3">
                                    <span class="text-sm text-gray-700">Diseño web corporativo</span>
                                    <span class="text-sm font-semibold text-gray-900">850,00 €</span>
                                </div>
                                <div class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3">
                                    <span class="text-sm text-gray-700">Mantenimiento mensual</span>
                                    <span class="text-sm font-semibold text-gray-900">120,00 €</span>
                                </div>
                                <div class="rounded-xl bg-indigo-50 border border-indigo-100 px-4 py-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Base imponible</span>
                                        <span>970,00 €</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>IVA</span>
                                        <span>203,70 €</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-gray-900">
                                        <span>Total</span>
                                        <span>1.173,70 €</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 bg-gradient-to-br from-gray-50 to-white">
                            <p class="text-sm font-semibold text-indigo-600 mb-3">Resultado final</p>
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">Comparte y descarga en PDF</h2>
                            <p class="text-gray-600 mb-6">
                                Envía el presupuesto a tu cliente mediante un enlace único o descárgalo en PDF con una presentación limpia y profesional.
                            </p>

                            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">Presupuesto #2026-001</p>
                                        <p class="text-sm text-gray-500">Cliente: Empresa Demo SL</p>
                                    </div>
                                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-green-100 text-green-700">Listo para enviar</span>
                                </div>

                                <div class="space-y-2 text-sm text-gray-600 mb-5">
                                    <div class="flex justify-between">
                                        <span>Enlace para cliente</span>
                                        <span class="text-indigo-600 font-medium">Activo</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>PDF descargable</span>
                                        <span class="text-indigo-600 font-medium">Disponible</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Seguimiento</span>
                                        <span class="text-indigo-600 font-medium">Enviado</span>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="button" class="flex-1 rounded-xl bg-indigo-600 text-white px-4 py-3 text-sm font-semibold">
                                        Enviar presupuesto
                                    </button>
                                    <button type="button" class="flex-1 rounded-xl border border-gray-200 bg-white text-gray-700 px-4 py-3 text-sm font-semibold">
                                        Descargar PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Logos / proof --}}
    <section class="py-8 bg-white border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                Diseñado para profesionales que necesitan crear presupuestos rápidos, claros y profesionales.
            </p>
        </div>
    </section>

    {{-- Features --}}
    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Funcionalidades
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Todo lo que necesitas para crear presupuestos profesionales
                </h2>
                <p class="text-gray-600">
                    Una herramienta simple para autónomos y pequeñas empresas que quieren crear presupuestos online, enviarlos fácilmente y mantener el control de su actividad comercial.
                </p>
            </div>

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
                    'desc' => 'Registra rápidamente incidencias, dudas y comentarios dentro de la plataforma para no perder contexto.',
                    'color' => 'orange'
                ],
            ];
            @endphp

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $feature)
                    <article class="bg-white border border-gray-100 rounded-2xl p-6 hover:shadow-xl hover:shadow-indigo-50 transition-all group">
                        <div class="w-12 h-12 bg-{{ $feature['color'] }}-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-{{ $feature['color'] }}-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $feature['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section id="how-it-works" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Cómo funciona
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Crea tu primer presupuesto en tres pasos
                </h2>
                <p class="text-gray-600">
                    Empieza en minutos y olvídate de las plantillas complicadas. Todo está pensado para que puedas enviar presupuestos rápidos y profesionales desde el primer día.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <article class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Crea tu cliente</h3>
                    <p class="text-gray-600">
                        Guarda los datos de tus clientes y reutilízalos cada vez que necesites generar un nuevo presupuesto.
                    </p>
                </article>

                <article class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Añade conceptos y totales</h3>
                    <p class="text-gray-600">
                        Inserta tus servicios o productos habituales, aplica IVA, descuentos y deja el presupuesto listo en pocos clics.
                    </p>
                </article>

                <article class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Envía o descarga en PDF</h3>
                    <p class="text-gray-600">
                        Comparte el presupuesto con tu cliente mediante un enlace único o descárgalo en PDF para enviarlo por correo o imprimirlo.
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- For who --}}
    <section class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-600 p-8 sm:p-12 text-white shadow-2xl">
                <div class="max-w-3xl">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">Una herramienta hecha para profesionales reales</h2>
                    <p class="text-indigo-100 text-lg mb-8">
                        Mis presupuestos está pensado para quienes necesitan crear presupuestos de forma rápida, clara y profesional sin perder tiempo en tareas repetitivas.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach([
                        'Autónomos',
                        'Freelancers',
                        'Pequeñas pymes',
                        'Equipos comerciales',
                        'Empresas de servicios',
                        'Consultores',
                        'Diseñadores',
                        'Profesionales multiempresa'
                    ] as $item)
                        <div class="rounded-2xl bg-white/10 border border-white/15 px-4 py-4 text-sm font-medium">
                            {{ $item }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- CTA mid --}}
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Empieza gratis y crea tu primer presupuesto hoy</h2>
            <p class="text-gray-600 mb-8 text-lg">
                Regístrate en minutos y descubre una forma más rápida de trabajar con tus clientes.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Crear cuenta gratis →
            </a>
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

            @if($promoPlan && $promoSettings['months'] > 0)
                <div class="mb-12 relative overflow-hidden bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 shadow-xl text-white">
                    <div class="absolute inset-0 pointer-events-none" aria-hidden="true">
                        <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                    </div>

                    <div class="relative flex flex-col sm:flex-row items-center gap-6">
                        <div class="flex-1 text-center sm:text-left">
                            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold mb-3">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                                </svg>
                                Oferta de lanzamiento
                            </div>

                            <h2 class="text-2xl sm:text-3xl font-bold mb-2">
                                {{ $promoSettings['months'] }} {{ $promoSettings['months'] == 1 ? 'mes' : 'meses' }} gratis con el plan <span class="text-yellow-300">{{ $promoPlan->name }}</span>
                            </h2>

                            <p class="text-indigo-100 max-w-2xl">
                                Regístrate ahora y disfruta de {{ $promoSettings['months'] }} {{ $promoSettings['months'] == 1 ? 'mes' : 'meses' }} del plan {{ $promoPlan->name }} completamente gratis. Sin tarjeta de crédito y sin compromiso.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center gap-2 bg-white text-indigo-700 font-bold px-7 py-3.5 rounded-xl hover:bg-indigo-50 transition shadow-lg">
                                Comenzar gratis →
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-center mb-16 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Precios
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Planes y precios</h2>
                <p class="text-gray-600">
                    Elige el plan que mejor se adapta a tu forma de trabajar y empieza a crear presupuestos en minutos.
                </p>
            </div>

            @php
            $plans = \MultiempresaApp\Plans\Models\Plan::active()->orderBy('price_monthly')->get();
            @endphp

            @if($plans->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach($plans as $plan)
                        @php
                            $isPro = $loop->iteration === 2 && $plans->count() > 1;
                        @endphp

                        <article class="relative bg-white rounded-3xl border {{ $isPro ? 'border-indigo-500 shadow-xl shadow-indigo-100' : 'border-gray-200 shadow-sm' }} p-8 flex flex-col">
                            @if($isPro)
                                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                                    <span class="bg-indigo-600 text-white text-xs font-bold px-4 py-1.5 rounded-full shadow">
                                        Más popular
                                    </span>
                                </div>
                            @endif

                            <div class="mb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $plan->description }}</p>

                                <div class="flex items-end gap-1">
                                    @if($plan->isFree())
                                        <span class="text-4xl font-bold text-gray-900">Gratis</span>
                                    @else
                                        <span class="text-4xl font-bold text-gray-900">€{{ number_format($plan->price_monthly, 2) }}</span>
                                        <span class="text-sm text-gray-500 mb-1">/mes</span>
                                    @endif
                                </div>

                                @if(!$plan->isFree() && $plan->price_yearly > 0)
                                    <p class="text-xs text-green-600 mt-1">
                                        O €{{ number_format($plan->price_yearly, 2) }}/año
                                        (ahorra {{ round((1 - ($plan->price_yearly / ($plan->price_monthly * 12))) * 100) }}%)
                                    </p>
                                @endif
                            </div>

                            <ul class="space-y-3 mb-8 flex-1">
                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Hasta {{ $plan->max_users >= 999 ? 'ilimitados' : $plan->max_users }} usuarios
                                </li>

                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ($plan->max_presupuestos ?? 0) == 0 ? 'Presupuestos ilimitados' : 'Hasta ' . $plan->max_presupuestos . ' presupuestos/mes' }}
                                </li>

                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ($plan->max_empresas ?? 0) == 0 ? 'Empresas ilimitadas' : 'Hasta ' . $plan->max_empresas . ' empresas' }}
                                </li>

                                @if($plan->features)
                                    @foreach($plan->features as $feature)
                                        <li class="flex items-center gap-2 text-sm text-gray-600">
                                            <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                @endif
                            </ul>

                            <a href="{{ route('register') }}"
                               class="block text-center py-3.5 px-6 rounded-xl font-semibold transition {{ $isPro ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ $plan->isFree() ? 'Empezar gratis' : 'Elegir plan' }}
                            </a>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    @foreach([
                        ['name'=>'Gratuito','price'=>'Gratis','features'=>['Hasta 1 usuario','Hasta 15 presupuestos/mes','Hasta 1 empresa'],'highlight'=>false],
                        ['name'=>'Profesional','price'=>'€6,00/mes','features'=>['Hasta 3 usuarios','Presupuestos ilimitados','Empresas ilimitadas'],'highlight'=>true],
                        ['name'=>'Empresarial','price'=>'€9,00/mes','features'=>['Hasta 5 usuarios','Presupuestos ilimitados','Empresas ilimitadas'],'highlight'=>false],
                    ] as $plan)
                        <article class="bg-white rounded-3xl border {{ $plan['highlight'] ? 'border-indigo-500 shadow-xl shadow-indigo-100' : 'border-gray-200' }} p-8 flex flex-col">
                            <h3 class="text-xl font-bold mb-2">{{ $plan['name'] }}</h3>
                            <div class="text-3xl font-bold text-gray-900 mb-6">{{ $plan['price'] }}</div>

                            <ul class="space-y-3 mb-8 flex-1">
                                @foreach($plan['features'] as $f)
                                    <li class="flex items-center gap-2 text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $f }}
                                    </li>
                                @endforeach
                            </ul>

                            <a href="{{ route('register') }}" class="block text-center py-3 rounded-xl font-semibold {{ $plan['highlight'] ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition">
                                Empezar
                            </a>
                        </article>
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
                <div class="text-center mb-12 max-w-3xl mx-auto">
                    <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                        Últimas noticias
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Novedades y actualizaciones</h2>
                    <p class="text-gray-600">
                        Mantente al día con las últimas novedades de la plataforma, mejoras y nuevas funcionalidades.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($noticias as $noticia)
                        <article>
                            <a href="{{ route('noticias.show', $noticia->slug) }}"
                               class="group block bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all hover:-translate-y-0.5">
                                @if($noticia->imagen)
                                    <div class="aspect-video overflow-hidden">
                                        <img
                                            src="{{ Storage::url($noticia->imagen) }}"
                                            alt="{{ $noticia->titulo }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                            loading="lazy"
                                        >
                                    </div>
                                @else
                                    <div class="aspect-video bg-gradient-to-br from-indigo-50 via-purple-50 to-blue-50 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                @endif

                                <div class="p-5">
                                    @if($noticia->publicado_en)
                                        <time class="text-xs text-indigo-500 font-medium mb-2 block" datetime="{{ $noticia->publicado_en->toDateString() }}">
                                            {{ $noticia->publicado_en->format('d \d\e F \d\e Y') }}
                                        </time>
                                    @endif

                                    <h3 class="font-semibold text-gray-900 text-base leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors mb-2">
                                        {{ $noticia->titulo }}
                                    </h3>

                                    @if($noticia->meta_description)
                                        <p class="text-sm text-gray-500 line-clamp-2">{{ $noticia->meta_description }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($noticia->contenido), 110) }}</p>
                                    @endif

                                    <span class="inline-flex items-center gap-1 text-xs text-indigo-600 font-medium mt-3">
                                        Leer más
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </span>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- FAQ --}}
    <section id="faq" class="py-24 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Preguntas frecuentes
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Resolvemos tus dudas
                </h2>
                <p class="text-gray-600">
                    Todo lo necesario para empezar a usar la plataforma con tranquilidad.
                </p>
            </div>

            <div class="space-y-4">
                @foreach([
                    [
                        'q' => '¿Qué es Mis presupuestos?',
                        'a' => 'Es un software de presupuestos online pensado para autónomos y pequeñas empresas. Permite crear, enviar y descargar presupuestos profesionales de forma rápida.'
                    ],
                    [
                        'q' => '¿Necesito instalar algo?',
                        'a' => 'No. Funciona directamente desde el navegador, así que puedes empezar a trabajar sin instalaciones complicadas.'
                    ],
                    [
                        'q' => '¿Puedo descargar los presupuestos en PDF?',
                        'a' => 'Sí. Todos los presupuestos pueden descargarse en PDF con un formato profesional listo para enviar o imprimir.'
                    ],
                    [
                        'q' => '¿Puedo gestionar varias empresas?',
                        'a' => 'Sí. La plataforma incluye soporte multiempresa para que puedas trabajar con varios negocios desde una misma cuenta.'
                    ],
                    [
                        'q' => '¿Hay un plan gratuito?',
                        'a' => 'Sí. Puedes empezar con un plan gratuito y, si lo necesitas, pasar más adelante a un plan superior.'
                    ],
                ] as $faq)
                    <details class="group bg-white border border-gray-200 rounded-2xl p-6">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-gray-900">
                            {{ $faq['q'] }}
                            <span class="text-indigo-600 transition group-open:rotate-45 text-xl">+</span>
                        </summary>
                        <p class="mt-4 text-gray-600 leading-relaxed">
                            {{ $faq['a'] }}
                        </p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="py-24 bg-indigo-600">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                Empieza a enviar presupuestos hoy mismo
            </h2>
            <p class="text-indigo-100 mb-8 text-lg">
                Crea tu cuenta gratuita y empieza a trabajar con presupuestos profesionales en minutos.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg">
                    Crear cuenta gratis →
                </a>
                <a href="#pricing" class="border border-white/20 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-white/10 transition">
                    Ver precios
                </a>
            </div>
        </div>
    </section>
</main>

{{-- Footer --}}
<footer class="bg-white border-t border-gray-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ config('app.name') }} — Un producto de anixelo.com</p>

            <nav class="flex flex-wrap items-center justify-center gap-4" aria-label="Enlaces del pie">
                <a href="#features" class="hover:text-indigo-600 transition">Funcionalidades</a>
                <a href="#pricing" class="hover:text-indigo-600 transition">Precios</a>
                <a href="#faq" class="hover:text-indigo-600 transition">Preguntas frecuentes</a>
                <span class="text-gray-300 hidden sm:inline">|</span>
                <a href="{{ route('pages.privacy') }}" class="hover:text-indigo-600 transition">Política de Privacidad</a>
                <a href="{{ route('pages.terms') }}" class="hover:text-indigo-600 transition">Términos y Condiciones</a>
                <a href="{{ route('pages.contact') }}" class="hover:text-indigo-600 transition">Contacto</a>
                <span class="text-gray-300 hidden sm:inline">|</span>
                <a href="{{ route('login') }}" class="hover:text-indigo-600 transition">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="hover:text-indigo-600 transition">Registrarse</a>
            </nav>
        </div>
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