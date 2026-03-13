<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de Privacidad — {{ config('app.name') }}</title>
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
<a href="/" class="flex items-center gap-3 text-indigo-600 font-extrabold text-xl" aria-label="Ir a inicio">
    <img 
        src="/pwa-icons/icon-192x192.png" 
        alt="Logo {{ config('app.name') }}" 
        class="w-8 h-8 shrink-0"
    >
    <span>{{ config('app.name') }}</span>
</a>
                <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-indigo-600 transition">← Volver al inicio</a>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Política de Privacidad</h1>
        <p class="text-sm text-gray-500 mb-8">Última actualización: {{ date('d/m/Y') }}</p>

        <div class="space-y-8 text-gray-700 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Responsable del tratamiento</h2>
                <p>{{ config('app.name') }} es el responsable del tratamiento de los datos personales recogidos a través de este sitio web.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Datos que recopilamos</h2>
                <p>Recopilamos únicamente los datos personales necesarios para prestar nuestros servicios, que incluyen:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Nombre y apellidos</li>
                    <li>Dirección de correo electrónico</li>
                    <li>Datos de la empresa u organización</li>
                    <li>Datos de facturación y presupuestos</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Finalidad del tratamiento</h2>
                <p>Los datos personales recogidos se utilizan exclusivamente para:</p>
                <ul class="list-disc pl-6 mt-2 space-y-1">
                    <li>Gestionar tu cuenta y acceso a la plataforma</li>
                    <li>Prestarte los servicios contratados</li>
                    <li>Enviarte comunicaciones relacionadas con el servicio</li>
                    <li>Cumplir con las obligaciones legales aplicables</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Base legal del tratamiento</h2>
                <p>El tratamiento de tus datos se basa en la ejecución del contrato de prestación de servicios, tu consentimiento y el cumplimiento de obligaciones legales.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Conservación de los datos</h2>
                <p>Los datos se conservarán mientras dure la relación contractual y durante el tiempo necesario para cumplir con las obligaciones legales vigentes.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Tus derechos</h2>
                <p>Puedes ejercer tus derechos de acceso, rectificación, supresión, oposición, limitación del tratamiento y portabilidad de datos contactando con nosotros a través de nuestra página de <a href="{{ route('pages.contact') }}" class="text-indigo-600 hover:underline">contacto</a>.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Seguridad</h2>
                <p>Implementamos las medidas técnicas y organizativas adecuadas para garantizar la seguridad de tus datos personales y protegerlos frente a accesos no autorizados, pérdida o destrucción.</p>
            </section>
        </div>
    </main>

    <footer class="bg-gray-50 border-t border-gray-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>© {{ date('Y') }} {{ config('app.name') }} — By Anixelo</p>
                <nav class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition">Inicio</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-indigo-600 transition font-medium text-indigo-600">Política de Privacidad</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-indigo-600 transition">Términos y Condiciones</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-indigo-600 transition">Contacto</a>
                </nav>
            </div>
        </div>
    </footer>

</body>
</html>
