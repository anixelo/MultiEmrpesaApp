<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contacto — {{ config('app.name') }}</title>
    <meta name="robots" content="noindex,follow">
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
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Contacto</h1>
        <p class="text-sm text-gray-500 mb-8">¿Tienes alguna pregunta o necesitas ayuda? Estamos aquí para ayudarte.</p>

        @if(session('contact_success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4">
            <p class="text-sm text-green-700 font-medium">{{ session('contact_success') }}</p>
        </div>
        @endif

        <div class="grid sm:grid-cols-2 gap-8">
            {{-- Contact info --}}
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Correo electrónico</h3>
                        <p class="text-sm text-gray-600 mt-0.5">info@anixelo.com</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">Horario de atención</h3>
                        <p class="text-sm text-gray-600 mt-0.5">Lunes a viernes, 9:00 – 18:00</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">¿Ya eres cliente?</h3>
                        <p class="text-sm text-gray-600 mt-0.5">
                            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Inicia sesión</a> para acceder al soporte desde tu panel.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Contact form --}}
            <form method="POST" action="{{ route('pages.contact.send') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="contact_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" id="contact_name" name="name" required
                           value="{{ old('name') }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <input type="email" id="contact_email" name="email" required
                           value="{{ old('email') }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_subject" class="block text-sm font-medium text-gray-700 mb-1">Asunto</label>
                    <input type="text" id="contact_subject" name="subject" required
                           value="{{ old('subject') }}"
                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('subject')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="contact_message" class="block text-sm font-medium text-gray-700 mb-1">Mensaje</label>
                    <textarea id="contact_message" name="message" rows="4" required
                              class="w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm resize-none">{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Enviar mensaje
                </button>
            </form>
        </div>
    </main>

    <footer class="bg-gray-50 border-t border-gray-200 py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <p>© {{ date('Y') }} {{ config('app.name') }} — By Anixelo</p>
                <nav class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ url('/') }}" class="hover:text-indigo-600 transition">Inicio</a>
                    <a href="{{ route('pages.privacy') }}" class="hover:text-indigo-600 transition">Política de Privacidad</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-indigo-600 transition">Términos y Condiciones</a>
                    <a href="{{ route('pages.contact') }}" class="hover:text-indigo-600 transition font-medium text-indigo-600">Contacto</a>
                </nav>
            </div>
        </div>
    </footer>

</body>
</html>
