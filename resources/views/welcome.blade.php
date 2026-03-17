<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $appName = config('app.name', 'Mis presupuestos');
        $appUrl = url('/');
        $appTitle = 'Software de presupuestos online para autónomos y pymes | ' . $appName;
        $appDescription = 'Crea presupuestos online, envíalos a tus clientes, descárgalos en PDF y organiza notas previas, plantillas y empresas desde una sola herramienta. Ideal para autónomos y pymes.';
        $appImage = asset('images/og-mis-presupuestos.jpg');
        $logoImage = asset('icons/icon-512x512.png');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $appTitle }}</title>
    <meta name="description" content="{{ $appDescription }}">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
    <meta name="author" content="Anixelo">
    <link rel="canonical" href="{{ $appUrl }}">

    <meta property="og:locale" content="es_ES">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $appTitle }}">
    <meta property="og:description" content="{{ $appDescription }}">
    <meta property="og:url" content="{{ $appUrl }}">
    <meta property="og:image" content="{{ $appImage }}">
    <meta property="og:image:alt" content="Software de presupuestos online para autónomos y pymes">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $appTitle }}">
    <meta name="twitter:description" content="{{ $appDescription }}">
    <meta name="twitter:image" content="{{ $appImage }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@type":"WebSite",
      "name":"{{ $appName }}",
      "url":"{{ $appUrl }}"
    }
    </script>

    <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@type":"Organization",
      "name":"Anixelo",
      "url":"https://anixelo.com",
      "logo":"{{ $logoImage }}"
    }
    </script>

    <script type="application/ld+json">
    {
      "@context":"https://schema.org",
      "@type":"SoftwareApplication",
      "name":"{{ $appName }}",
      "applicationCategory":"BusinessApplication",
      "applicationSubCategory":"Estimating software",
      "operatingSystem":"Web, Android, iOS, Windows, macOS",
      "url":"{{ $appUrl }}",
      "image":"{{ $appImage }}",
      "screenshot":"{{ $appImage }}",
      "description":"{{ $appDescription }}",
      "brand":{
        "@type":"Brand",
        "name":"{{ $appName }}"
      },
      "publisher":{
        "@type":"Organization",
        "name":"Anixelo",
        "url":"https://anixelo.com"
      },
      "offers":{
        "@type":"Offer",
        "price":"0",
        "priceCurrency":"EUR",
        "url":"{{ route('register') }}"
      },
      "featureList":[
        "Crear presupuestos online",
        "Descargar presupuestos en PDF",
        "Enviar presupuestos por enlace",
        "Guardar notas previas",
        "Reutilizar plantillas",
        "Gestionar varias empresas",
        "Usar la aplicación como PWA"
      ]
    }
    </script>

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
            "text":"Mis presupuestos es un software de presupuestos online para autónomos y pymes que permite crear, enviar y descargar presupuestos profesionales en PDF."
          }
        },
        {
          "@type":"Question",
          "name":"¿Cómo hacer un presupuesto profesional para un cliente?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Con Mis presupuestos puedes crear un presupuesto profesional añadiendo cliente, conceptos, impuestos, observaciones y totales de forma clara y rápida."
          }
        },
        {
          "@type":"Question",
          "name":"¿Puedo crear presupuestos online y descargarlos en PDF?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. Puedes crear presupuestos online y descargarlos en PDF con un formato limpio y profesional listo para enviar o imprimir."
          }
        },
        {
          "@type":"Question",
          "name":"¿Puedo guardar notas antes de preparar un presupuesto?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. Puedes guardar notas previas con requisitos, ideas y observaciones para preparar mejor cada propuesta."
          }
        },
        {
          "@type":"Question",
          "name":"¿Sirve para autónomos y pequeñas empresas?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. La plataforma está pensada para autónomos, freelancers, consultores y pequeñas empresas que necesitan hacer presupuestos de forma rápida y profesional."
          }
        },
        {
          "@type":"Question",
          "name":"¿Puedo usar la aplicación desde el móvil?",
          "acceptedAnswer":{
            "@type":"Answer",
            "text":"Sí. Puedes acceder desde el navegador o instalar la aplicación como PWA en móvil, tablet o escritorio."
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

<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="/" class="flex items-center gap-3 text-indigo-600 font-extrabold text-xl" aria-label="Ir a inicio">
                <img
                    src="/pwa-icons/icon-192x192.png"
                    alt="Logo {{ $appName }}"
                    class="w-8 h-8 shrink-0"
                    width="32"
                    height="32"
                >
                <span>{{ $appName }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6" aria-label="Navegación principal">
                <a href="#features" class="text-sm text-gray-600 hover:text-indigo-600 transition">Funcionalidades</a>
                <a href="#how-it-works" class="text-sm text-gray-600 hover:text-indigo-600 transition">Cómo funciona</a>
                <a href="#why-use-it" class="text-sm text-gray-600 hover:text-indigo-600 transition">Por qué usarlo</a>
                <a href="#comparison" class="text-sm text-gray-600 hover:text-indigo-600 transition">Comparativa</a>
                <a href="#pricing" class="text-sm text-gray-600 hover:text-indigo-600 transition">Precios</a>
                <a href="#faq" class="text-sm text-gray-600 hover:text-indigo-600 transition">Preguntas frecuentes</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Ir al panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-block bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                        Registrarse
                    </a>
                @endauth
            </nav>

            @auth
                <a href="{{ route('dashboard') }}" class="md:hidden bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Panel</a>
            @else
                <div class="md:hidden flex items-center gap-2">
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 px-3 py-2 rounded-lg border border-gray-200">Entrar</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-block bg-indigo-600 text-white text-sm px-4 py-2 rounded-lg">Registro</a>
                </div>
            @endauth
        </div>
    </div>
</header>

<main id="main-content">
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
                    Para autónomos, freelancers y pequeñas empresas
                </span>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                    Software de presupuestos online para autónomos y pymes
                </h1>

                <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto mb-10">
                    Crea presupuestos online, envíalos a tus clientes, descárgalos en PDF, reutiliza plantillas y guarda notas previas para preparar propuestas más claras, rápidas y profesionales.
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
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Presupuestos en PDF</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Notas previas</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Plantillas reutilizables</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Multiempresa</span>
                    <span class="inline-flex items-center gap-2 bg-white/80 border border-gray-200 rounded-full px-3 py-1.5">Instalable como app</span>
                </div>
            </div>

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
                            <p class="text-sm font-semibold text-indigo-600 mb-3">Preparación y edición</p>
                            <h2 class="text-2xl font-bold text-gray-900 mb-3">Prepara mejor cada propuesta antes de enviarla</h2>
                            <p class="text-gray-600 mb-6">
                                Guarda requisitos, ideas, observaciones y detalles del cliente antes de crear el presupuesto. Después añade conceptos, impuestos y totales desde una interfaz clara y rápida.
                            </p>

                            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 mb-6">
                                <p class="text-xs font-semibold text-amber-700 mb-2">Notas previas</p>
                                <ul class="space-y-1 text-sm text-gray-700">
                                    <li>• Cliente quiere renovar la web corporativa</li>
                                    <li>• Necesita formulario de contacto y blog</li>
                                    <li>• Posible mantenimiento mensual tras la entrega</li>
                                </ul>
                            </div>

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
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Envía, comparte y descarga presupuestos profesionales</h3>
                            <p class="text-gray-600 mb-6">
                                Envía el presupuesto mediante un enlace único, descárgalo en PDF y accede a la plataforma como PWA desde móvil, tablet o escritorio.
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
                                    <div class="flex justify-between">
                                        <span>Acceso como app</span>
                                        <span class="text-indigo-600 font-medium">Disponible</span>
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

<section class="py-12 bg-white border-y border-gray-100">
    <div class="max-w-6xl mx-auto px-4">

        <div class="grid md:grid-cols-3 gap-6 text-center">

            <div class="rounded-2xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-2">
                    Presupuestos más rápidos
                </h3>
                <p class="text-sm text-gray-600">
                    Crea propuestas en minutos reutilizando clientes, conceptos y plantillas.
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-2">
                    Imagen más profesional
                </h3>
                <p class="text-sm text-gray-600">
                    Descarga presupuestos en PDF con un formato claro listo para enviar a tus clientes.
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-900 mb-2">
                    Mejor organización
                </h3>
                <p class="text-sm text-gray-600">
                    Guarda notas, reutiliza plantillas y mantén todos tus presupuestos organizados.
                </p>
            </div>

        </div>

    </div>
</section>

    <section class="py-8 bg-white border-y border-gray-100" aria-label="Propuesta de valor">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                Un programa de presupuestos pensado para profesionales que necesitan crear propuestas claras, rápidas y profesionales sin depender de plantillas en Word o Excel.
            </p>
        </div>
    </section>

    <section id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Funcionalidades
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Todo lo que necesitas para hacer presupuestos online
                </h2>
                <p class="text-gray-600">
                    Una herramienta para autónomos y pequeñas empresas que quieren crear presupuestos online, enviarlos a clientes, descargarlos en PDF y mantener mejor organizada su actividad comercial.
                </p>
            </div>

@php
$features = [
    [
        'icon' => 'M9 12h6m-6 4h6M9 8h6M7 3h10a2 2 0 012 2v14l-5-3-5 3V5a2 2 0 012-2z',
        'title' => 'Presupuestos profesionales',
        'desc' => 'Crea presupuestos claros y profesionales con cálculo automático de importes, descuentos, impuestos y totales.',
        'color' => 'blue'
    ],
    [
        'icon' => 'M4 5h16M4 9h10M4 13h16M4 17h10',
        'title' => 'Notas previas',
        'desc' => 'Guarda ideas, requisitos y observaciones antes de preparar el presupuesto para no dejarte nada importante.',
        'color' => 'amber'
    ],
    [
        'icon' => 'M5 4h10a2 2 0 012 2v12l-4-2-4 2V6a2 2 0 012-2zm3 4h6m-6 4h6',
        'title' => 'Plantillas reutilizables',
        'desc' => 'Reutiliza presupuestos y estructuras habituales para ahorrar tiempo y trabajar con más consistencia.',
        'color' => 'violet'
    ],
    [
        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'title' => 'Gestión de clientes',
        'desc' => 'Guarda tus clientes y reutilízalos al crear nuevos presupuestos para trabajar más rápido.',
        'color' => 'green'
    ],
    [
        'icon' => 'M3 7h18M3 12h18M3 17h18',
        'title' => 'Conceptos reutilizables',
        'desc' => 'Añade servicios y productos habituales con precio, descripción e impuestos en pocos clics.',
        'color' => 'indigo'
    ],
    [
        'icon' => 'M12 8c-3.866 0-7 2.239-7 5s3.134 5 7 5 7-2.239 7-5-3.134-5-7-5zm0 8a3 3 0 100-6 3 3 0 000 6z',
        'title' => 'Seguimiento de presupuestos',
        'desc' => 'Consulta fácilmente si un presupuesto está en borrador, enviado, visto, aceptado o rechazado.',
        'color' => 'purple'
    ],
    [
        'icon' => 'M9 17v-2a4 4 0 014-4h4M7 12l3 3 7-7',
        'title' => 'Enlace para clientes',
        'desc' => 'Comparte presupuestos mediante un enlace único para que el cliente pueda verlos online.',
        'color' => 'yellow'
    ],
    [
        'icon' => 'M12 8v8m0 0l3-3m-3 3l-3-3M4 4h16v16H4z',
        'title' => 'Descarga en PDF',
        'desc' => 'Descarga cada presupuesto en PDF con un formato limpio y profesional listo para enviar o imprimir.',
        'color' => 'red'
    ],
    [
        'icon' => 'M7 4h10M5 8h14M4 12h16M7 16h10M9 20h6',
        'title' => 'Instalable como app',
        'desc' => 'Usa la plataforma como PWA desde móvil, tablet o escritorio para acceder más rápido.',
        'color' => 'cyan'
    ],
    [
        'icon' => 'M4 7h16M4 11h16M4 15h16M4 19h16',
        'title' => 'Multiempresa',
        'desc' => 'Gestiona presupuestos para varias empresas desde una sola cuenta.',
        'color' => 'teal'
    ],
    [
        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2M7 20H2v-2a3 3 0 015.356-1.857M15 7a3 3 0 11-6 0 3 3 0 016 0z',
        'title' => 'Trabajo en equipo',
        'desc' => 'Permite que varios usuarios gestionen presupuestos dentro de la misma empresa.',
        'color' => 'pink'
    ],
    [
        'icon' => 'M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V9m-5-4h6m0 0v6m0-6L10 14',
        'title' => 'Ahorro de tiempo diario',
        'desc' => 'Reduce tareas repetitivas reutilizando clientes, plantillas y configuraciones habituales.',
        'color' => 'emerald'
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
                    Empieza en minutos y olvídate de las plantillas complicadas. Todo está pensado para que puedas preparar y enviar presupuestos profesionales desde el primer día.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <article class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5">1</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Guarda notas previas</h3>
                    <p class="text-gray-600">
                        Anota requisitos, ideas y observaciones antes de empezar para preparar mejor cada presupuesto.
                    </p>
                </article>

                <article class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5">2</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Crea el presupuesto</h3>
                    <p class="text-gray-600">
                        Añade cliente, conceptos, impuestos, observaciones y totales desde una interfaz rápida y clara.
                    </p>
                </article>

                <article class="bg-white rounded-2xl p-8 border border-gray-200 shadow-sm">
                    <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg mb-5">3</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Envíalo o descárgalo</h3>
                    <p class="text-gray-600">
                        Comparte el presupuesto por enlace, descárgalo en PDF o accede desde cualquier dispositivo.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <section id="why-use-it" class="py-24 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Por qué usarlo
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Por qué usar un software de presupuestos en lugar de Excel o Word
                </h2>
                <p class="text-gray-600">
                    Muchas pequeñas empresas todavía preparan presupuestos manualmente. Eso hace perder tiempo, aumenta errores y complica el seguimiento de cada propuesta.
                </p>
            </div>

            <div class="prose prose-lg max-w-none text-gray-600 ">
                <p>
                    Con un software de presupuestos online puedes crear presupuestos más rápido, mantener una imagen profesional y reutilizar clientes, conceptos y plantillas sin empezar de cero en cada propuesta. Además, puedes controlar mejor qué presupuestos has enviado, cuáles siguen en borrador y cuáles han sido aceptados o rechazados.
                </p>
                <p class="pt-4">
                    {{ $appName }} está pensado para autónomos, freelancers y pymes que necesitan una herramienta sencilla para presupuestar servicios o proyectos, descargar presupuestos en PDF y compartirlos con clientes de forma clara. También permite guardar notas previas, algo especialmente útil cuando una propuesta requiere recopilar requisitos antes de calcular importes o definir el alcance del trabajo.
                </p>
            </div>
        </div>
    </section>

    <section id="comparison" class="py-24 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-2 bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-4">
                    Comparativa
                </span>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Deja atrás Excel y Word para hacer presupuestos
                </h2>
                <p class="text-gray-600">
                    Ahorra tiempo, reduce errores y trabaja con una herramienta pensada para crear presupuestos profesionales.
                </p>
            </div>

            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                <div class="grid md:grid-cols-2">
                    <div class="border-b md:border-b-0 md:border-r border-gray-200 p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Con Excel o Word</h3>
                        <ul class="space-y-4 text-gray-600">
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">×</span>
                                <span>Copiar y pegar en cada presupuesto</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">×</span>
                                <span>Fórmulas y totales manuales</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">×</span>
                                <span>Archivos difíciles de organizar</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">×</span>
                                <span>Sin seguimiento del estado del presupuesto</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600 text-xs font-bold">×</span>
                                <span>Más riesgo de errores y olvidos</span>
                            </li>
                        </ul>
                    </div>

                    <div class="p-8 bg-indigo-50">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Con {{ $appName }}</h3>
                        <ul class="space-y-4 text-gray-700">
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-bold">✓</span>
                                <span>Plantillas reutilizables para ahorrar tiempo</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-bold">✓</span>
                                <span>Cálculo automático de importes, descuentos e impuestos</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-bold">✓</span>
                                <span>Clientes, conceptos y empresas organizados</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-bold">✓</span>
                                <span>Seguimiento de presupuestos enviados, vistos y aceptados</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600 text-xs font-bold">✓</span>
                                <span>Proceso más rápido, claro y profesional</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-600 p-8 sm:p-12 text-white shadow-2xl">
                <div class="max-w-3xl">
                    <h2 class="text-3xl sm:text-4xl font-bold mb-4">Una herramienta hecha para profesionales reales</h2>
                    <p class="text-indigo-100 text-lg mb-8">
                        {{ $appName }} está pensado para quienes necesitan hacer presupuestos de forma rápida, clara y profesional sin perder tiempo en tareas repetitivas.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach([
                        'Autónomos',
                        'Freelancers',
                        'Pequeñas empresas',
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

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Empieza gratis y crea presupuestos profesionales desde hoy</h2>
            <p class="text-gray-600 mb-8 text-lg">
                Regístrate en minutos, guarda notas previas, reutiliza plantillas y prepara presupuestos online listos para enviar a tus clientes.
            </p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center bg-indigo-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                Crear cuenta gratis →
            </a>
        </div>
    </section>

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
                    Elige el plan que mejor se adapta a tu forma de trabajar y empieza a crear presupuestos online en minutos.
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
                                        <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->price_monthly, 2) }} €</span>
                                        <span class="text-sm text-gray-500 mb-1">/mes</span>
                                    @endif
                                </div>

                                @if(!$plan->isFree() && $plan->price_yearly > 0)
                                    <p class="text-xs text-green-600 mt-1">
                                        O {{ number_format($plan->price_yearly, 2) }} €/año
                                        (ahorra {{ round((1 - ($plan->price_yearly / ($plan->price_monthly * 12))) * 100) }}%)
                                    </p>
                                @endif
                            </div>

                            <ul class="space-y-3 mb-8 flex-1">
                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $plan->max_users >= 999 ? 'Usuarios ilimitados' : 'Hasta ' . $plan->max_users . ' ' . ($plan->max_users == 1 ? 'usuario' : 'usuarios') }}
                                </li>

                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ($plan->max_presupuestos ?? 0) == 0 ? 'Presupuestos ilimitados' : 'Hasta ' . $plan->max_presupuestos . ' presupuestos al mes' }}
                                </li>

                                <li class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ($plan->max_empresas ?? 0) == 0 ? 'Empresas ilimitadas' : 'Hasta ' . $plan->max_empresas . ' ' . (($plan->max_empresas ?? 0) == 1 ? 'empresa' : 'empresas') }}
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
            @endif
        </div>
    </section>

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
                        Guías y artículos
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Consejos para hacer mejores presupuestos</h2>
                    <p class="text-gray-600">
                        Aprende a crear presupuestos profesionales, reutilizar plantillas, definir precios y organizar mejor tus propuestas comerciales.
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
                                            {{ \Carbon\Carbon::parse($noticia->publicado_en)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
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
                        'a' => 'Es un software de presupuestos online pensado para autónomos y pymes. Permite crear, enviar y descargar presupuestos profesionales de forma rápida.'
                    ],
                    [
                        'q' => '¿Cómo hacer un presupuesto profesional para un cliente?',
                        'a' => 'Solo tienes que añadir cliente, conceptos, importes, impuestos y observaciones. La plataforma calcula totales y te permite dejar el presupuesto listo para enviar.'
                    ],
                    [
                        'q' => '¿Puedo descargar los presupuestos en PDF?',
                        'a' => 'Sí. Todos los presupuestos pueden descargarse en PDF con un formato profesional listo para enviar, compartir o imprimir.'
                    ],
                    [
                        'q' => '¿Puedo guardar notas antes de preparar un presupuesto?',
                        'a' => 'Sí. Puedes guardar notas previas con requisitos, ideas y observaciones para preparar mejor cada propuesta.'
                    ],
                    [
                        'q' => '¿Puedo gestionar varias empresas?',
                        'a' => 'Sí. La plataforma incluye soporte multiempresa para que puedas trabajar con varios negocios desde una misma cuenta.'
                    ],
                    [
                        'q' => '¿Hay un plan gratuito?',
                        'a' => 'Sí. Puedes empezar con un plan gratuito y pasar a uno superior cuando necesites más usuarios, más empresas o más funcionalidades.'
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

    <section class="py-24 bg-indigo-600">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                Empieza gratis y crea tu primer presupuesto en minutos
            </h2>
            <p class="text-indigo-100 mb-8 text-lg">
                Sin tarjeta de crédito. Sin instalaciones. Crea presupuestos online, envíalos a tus clientes y descárgalos en PDF desde cualquier dispositivo.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-indigo-700 px-8 py-3.5 rounded-xl font-bold hover:bg-indigo-50 transition shadow-lg">
                    Crear cuenta gratis →
                </a>
                <a href="#pricing" class="border border-white/20 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-white/10 transition">
                    Ver precios
                </a>
            </div>
            <p class="text-sm text-indigo-100/90 mt-4">
                Plan gratuito disponible · Acceso desde móvil, tablet y escritorio
            </p>
        </div>
    </section>
</main>

<footer class="bg-white border-t border-gray-200 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ $appName }} · By Anixelo</p>

            <nav class="flex flex-wrap items-center justify-center gap-4" aria-label="Enlaces del pie">
                <a href="#features" class="hover:text-indigo-600 transition">Funcionalidades</a>
                <a href="#why-use-it" class="hover:text-indigo-600 transition">Por qué usarlo</a>
                <a href="#comparison" class="hover:text-indigo-600 transition">Comparativa</a>
                <a href="#pricing" class="hover:text-indigo-600 transition">Precios</a>
                <a href="#faq" class="hover:text-indigo-600 transition">Preguntas frecuentes</a>
                <span class="text-gray-300 hidden sm:inline">|</span>
                <a href="{{ route('pages.privacy') }}" class="hover:text-indigo-600 transition">Política de privacidad</a>
                <a href="{{ route('pages.terms') }}" class="hover:text-indigo-600 transition">Términos y condiciones</a>
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
        navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {});
    });
}
</script>

</body>
</html>