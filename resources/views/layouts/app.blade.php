<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'Mis presupuestos') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="apple-touch-icon" href="/pwa-icons/icon-192x192.png">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">

<div class="min-h-screen flex flex-col">

    {{-- Navigation --}}
    @include('layouts.navigation')

    {{-- Flash messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-green-50 border border-green-300 text-green-800 rounded-xl shadow-lg p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 z-50 max-w-sm w-full bg-red-50 border border-red-300 text-red-800 rounded-xl shadow-lg p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Page Heading --}}
    @isset($header)
    <header class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
            {{ $header }}
        </div>
    </header>
    @endisset

    {{-- Page Content --}}
    <main class="flex-1">
        {{ $slot }}
    </main>





<div id="pwa-install-banner" class="hidden fixed inset-x-0 bottom-6 z-50 px-4">
    <div class="mx-auto max-w-md">
        <div id="pwa-install-card" class="bg-white border border-gray-200 rounded-2xl shadow-xl p-5 flex gap-4 items-start">

                    <div id="pwa-install-icon" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                    <img 
                        src="/pwa-icons/icon-192x192.png" 
                        alt="Logo {{ config('app.name') }}" 
                        class="w-8 h-8 shrink-0"
                    >
                    </div>

            <div class="flex-1">
                <p id="pwa-install-title" class="text-sm font-semibold text-gray-900"></p>
                <p id="pwa-install-text" class="text-sm text-gray-500 mt-1"></p>
                <div id="pwa-install-actions" class="mt-4 flex gap-2 flex-wrap"></div>
            </div>

            <button
                id="pwa-install-close"
                class="text-gray-400 hover:text-gray-600 text-lg leading-none"
                type="button"
            >
                ✕
            </button>

        </div>
    </div>
</div>
<div id="pwa-inline-install" class="hidden max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 mb-8">
    <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4 sm:p-5 flex items-center justify-between gap-4">

        <div class="flex items-center gap-3">
                    <div id="pwa-install-icon" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600">
                    <img 
                        src="/pwa-icons/icon-192x192.png" 
                        alt="Logo {{ config('app.name') }}" 
                        class="w-8 h-8 shrink-0"
                    >
                    </div>

            <div>
                <p class="text-sm font-semibold text-indigo-900">
                    Instala Mis presupuestos
                </p>
                <p class="text-xs text-indigo-700">
                    Accede más rápido desde tu móvil.
                </p>
            </div>
        </div>

        <button
            id="pwa-inline-install-btn"
            type="button"
            class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 transition"
        >
            Instalar
        </button>

    </div>
</div>

<script>
let deferredPrompt = null;

document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('pwa-install-banner');
    const card = document.getElementById('pwa-install-card');
    const title = document.getElementById('pwa-install-title');
    const text = document.getElementById('pwa-install-text');
    const actions = document.getElementById('pwa-install-actions');
    const closeBtn = document.getElementById('pwa-install-close');

    const inlineInstall = document.getElementById('pwa-inline-install');
    const inlineInstallBtn = document.getElementById('pwa-inline-install-btn');

    const STORAGE_KEY = 'pwa_install_banner_mode';

    function isIos() {
        return /iphone|ipad|ipod/i.test(window.navigator.userAgent);
    }

    function isAppInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
    }

    function getBannerMode() {
        return localStorage.getItem(STORAGE_KEY) || 'full';
    }

    function setBannerMode(mode) {
        localStorage.setItem(STORAGE_KEY, mode);
    }

    function showBanner() {
        if (banner) banner.classList.remove('hidden');
    }

    function hideBanner() {
        if (banner) banner.classList.add('hidden');
    }

    function showInlineInstall() {
        if (inlineInstall) inlineInstall.classList.remove('hidden');
    }

    function hideInlineInstall() {
        if (inlineInstall) inlineInstall.classList.add('hidden');
    }

    function setContent({ heading, body, buttons = '' }) {
        if (!title || !text || !actions || !card) return;

        title.textContent = heading;
        text.textContent = body;
        actions.innerHTML = buttons;

        showBanner();
    }

    function renderFullInstallCard() {
        hideInlineInstall();

        setContent({
            heading: 'Instala Mis presupuestos',
            body: 'Accede más rápido, abre la app desde el móvil y disfruta de una experiencia más cómoda.',
            buttons: `
                <button
                    type="button"
                    id="pwa-install-btn"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Instalar app
                </button>

                <button
                    type="button"
                    id="pwa-later-btn"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Más tarde
                </button>
            `
        });

        bindInstallButton();

        const laterBtn = document.getElementById('pwa-later-btn');
        if (laterBtn) {
            laterBtn.addEventListener('click', () => {
                setBannerMode('inline');
                hideBanner();
                showInlineInstall();
            });
        }
    }

    function renderFullIosCard() {
        hideInlineInstall();

        setContent({
            heading: 'Añádela a tu pantalla de inicio',
            body: 'En iPhone, pulsa en Compartir y luego en “Añadir a pantalla de inicio”.',
            buttons: `
                <button
                    type="button"
                    id="pwa-ios-later-btn"
                    class="inline-flex items-center justify-center rounded-xl bg-gray-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-black"
                >
                    Más tarde
                </button>
            `
        });

        const btn = document.getElementById('pwa-ios-later-btn');
        if (btn) {
            btn.addEventListener('click', () => {
                setBannerMode('inline');
                hideBanner();
                showInlineInstall();
            });
        }
    }

    function bindInstallButton() {
        const installBtn = document.getElementById('pwa-install-btn');

        if (!installBtn) return;

        installBtn.addEventListener('click', async () => {
            try {
                if (!deferredPrompt) {
                    throw new Error('La instalación no está disponible en este momento');
                }

                deferredPrompt.prompt();
                const choice = await deferredPrompt.userChoice;

                if (choice.outcome === 'accepted') {
                    hideBanner();
                    hideInlineInstall();
                    localStorage.removeItem(STORAGE_KEY);
                } else {
                    setBannerMode('inline');
                    hideBanner();
                    showInlineInstall();
                }

                deferredPrompt = null;
            } catch (err) {
                console.error('PWA install error:', err);
            }
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            setBannerMode('inline');
            hideBanner();
            showInlineInstall();
        });
    }

    if (inlineInstallBtn) {
        inlineInstallBtn.addEventListener('click', async () => {
            try {
                if (isIos()) {
                    renderFullIosCard();
                    return;
                }

                if (!deferredPrompt) {
                    console.warn('Instalación no disponible');
                    return;
                }

                deferredPrompt.prompt();
                const choice = await deferredPrompt.userChoice;

                if (choice.outcome === 'accepted') {
                    hideInlineInstall();
                    hideBanner();
                    localStorage.removeItem(STORAGE_KEY);
                }

                deferredPrompt = null;
            } catch (err) {
                console.error('Error instalando PWA', err);
            }
        });
    }

    if (isAppInstalled()) {
        hideBanner();
        hideInlineInstall();
        return;
    }

    if (getBannerMode() === 'inline') {
        hideBanner();
        showInlineInstall();
        return;
    }

    if (isIos()) {
        renderFullIosCard();
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;

        if (isAppInstalled()) {
            hideBanner();
            hideInlineInstall();
            return;
        }

        if (getBannerMode() === 'inline') {
            hideBanner();
            showInlineInstall();
        } else {
            renderFullInstallCard();
        }
    });

    window.addEventListener('appinstalled', () => {
        deferredPrompt = null;
        hideBanner();
        hideInlineInstall();
        localStorage.removeItem(STORAGE_KEY);
    });
});
</script>






    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-gray-400">
            @auth
            <div class="mb-2 flex flex-wrap justify-center gap-4">
                @if(auth()->user()->isWorker())
                <a href="{{ route('worker.incidents.index') }}" class="text-gray-500 hover:text-indigo-600 transition">Incidencias</a>
                @endif
                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.incidents.index') }}" class="text-gray-500 hover:text-indigo-600 transition">Incidencias</a>
                <a href="{{ route('admin.subscription') }}" class="text-gray-500 hover:text-indigo-600 transition">Suscripción</a>
                @endif
                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('superadmin.incidents.index') }}" class="text-gray-500 hover:text-indigo-600 transition">Incidencias</a>
                @endif
            </div>
            @endauth
            © {{ date('Y') }} {{ config('app.name') }} — By Anixelo
        </div>
    </footer>
</div>

@livewireScripts
@if(session('impersonating_original_id'))
<div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50">
    <form method="POST" action="{{ route('impersonate.stop') }}">
        @csrf
        <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-full shadow-xl font-medium text-sm transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Dejar de suplantar a {{ auth()->user()->name }}
        </button>
    </form>
</div>
@endif
<script>
if ('serviceWorker' in navigator) {

    window.addEventListener('load', async () => {

        try {

            const registration = await navigator.serviceWorker.register('/sw.js');

            // Forzar revisión de actualizaciones
            registration.update();

            // Si hay un nuevo service worker esperando
            if (registration.waiting) {
                registration.waiting.postMessage({ type: 'SKIP_WAITING' });
            }

            // Si se instala uno nuevo
            registration.addEventListener('updatefound', () => {

                const newWorker = registration.installing;

                newWorker.addEventListener('statechange', () => {

                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // Recargar página para usar el nuevo SW
                        window.location.reload();
                    }

                });

            });

        } catch (e) {
            console.log('Service worker error', e);
        }

    });

}
</script>






@stack('scripts')
</body>
</html>
