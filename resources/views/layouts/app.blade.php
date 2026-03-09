<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' — ' : '' }}{{ config('app.name', 'MultiEmpresaApp') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    {{-- PWA --}}
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
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
            © {{ date('Y') }} {{ config('app.name') }} — Sistema Multi-Empresa
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
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .catch(() => {});
    });
}
</script>
@stack('scripts')
</body>
</html>
