<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Términos y Condiciones — {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased text-gray-900 bg-white">

    {{-- Navigation --}}
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-indigo-600 font-bold text-xl">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    {{ config('app.name') }}
                </a>
                <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">← Volver al inicio</a>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Términos y Condiciones</h1>
        <p class="text-sm text-gray-500 mb-8">Última actualización: {{ date('d/m/Y') }}</p>

        <div class="space-y-8 text-gray-700 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Aceptación de los términos</h2>
                <p>Al registrarte y utilizar {{ config('app.name') }}, aceptas estos términos y condiciones de uso. Si no estás de acuerdo con alguno de ellos, te rogamos que no utilices nuestros servicios.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Descripción del servicio</h2>
                <p>{{ config('app.name') }} es una plataforma de gestión empresarial que permite crear y gestionar presupuestos, clientes, servicios y tareas. El servicio se presta de forma online a través de nuestra plataforma web.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Registro y cuenta</h2>
                <p>Para acceder a nuestros servicios debes registrarte y crear una cuenta. Eres responsable de mantener la confidencialidad de tus credenciales de acceso y de todas las actividades que se realicen desde tu cuenta.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Uso permitido</h2>
                <p>Te comprometes a utilizar el servicio de forma lícita y conforme a estos términos. Queda prohibido:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Utilizar el servicio para actividades ilegales o fraudulentas</li>
                    <li>Intentar acceder a datos de otros usuarios</li>
                    <li>Interferir con el funcionamiento normal de la plataforma</li>
                    <li>Transmitir contenido malicioso o spam</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Planes y pagos</h2>
                <p>{{ config('app.name') }} ofrece diferentes planes de suscripción. Los precios y características de cada plan están disponibles en nuestra página de precios. Los pagos son procesados de forma segura y las suscripciones se renuevan automáticamente salvo cancelación previa.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Propiedad intelectual</h2>
                <p>Todos los derechos de propiedad intelectual sobre la plataforma, su diseño y tecnología son propiedad de {{ config('app.name') }}. Los contenidos que introduzcas en la plataforma son de tu propiedad.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Limitación de responsabilidad</h2>
                <p>{{ config('app.name') }} no será responsable de los daños indirectos, incidentales o consecuentes derivados del uso o imposibilidad de uso del servicio.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">8. Modificación de los términos</h2>
                <p>Nos reservamos el derecho de modificar estos términos en cualquier momento. Las modificaciones entrarán en vigor desde su publicación en esta página. Tu uso continuado del servicio tras la publicación de los cambios implica la aceptación de los mismos.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">9. Contacto</h2>
                <p>Para cualquier consulta sobre estos términos, puedes contactarnos a través de nuestra <a href="{{ route('pages.contact') }}" class="text-indigo-600 hover:underline">página de contacto</a>.</p>
            </section>
        </div>
    </main>

    <footer class="bg-gray-50 border-t border-gray-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>© {{ date('Y') }} {{ config('app.name') }} — Un producto de anixelo.com</p>
                <nav class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition">Inicio</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-indigo-600 transition">Política de Privacidad</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-indigo-600 transition font-medium text-indigo-600">Términos y Condiciones</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-indigo-600 transition">Contacto</a>
                </nav>
            </div>
        </div>
    </footer>

</body>
</html>
