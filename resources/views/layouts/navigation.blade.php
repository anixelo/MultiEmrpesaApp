<nav x-data="{ mobileOpen: false, profileOpen: false }" class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo & Primary nav --}}
            <div class="flex items-center gap-8">
                {{-- Logo --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-indigo-600 font-bold text-lg">
                    <img 
                        src="/pwa-icons/icon-192x192.png" 
                        alt="Logo {{ config('app.name') }}" 
                        class="w-8 h-8 shrink-0"
                    >
                    <span>{{ config('app.name') }}</span>
                </a>

                {{-- Desktop nav links --}}
                <div class="hidden md:flex items-center gap-1">
                    @auth
                        {{-- Worker dashboard --}}
                        @if(auth()->user()->isWorker())
                        <a href="{{ route('worker.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('worker.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Panel
                        </a>

                        <a href="{{ route('admin.presupuestos.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.presupuestos.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Presupuestos
                        </a>
                        {{-- Datos dropdown (worker) --}}
                        <div x-data="{ datosOpen: false }" class="relative">
                            <button @click="datosOpen = !datosOpen" @click.outside="datosOpen = false"
                                    class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium transition
                                           {{ request()->routeIs('admin.clientes.*') || request()->routeIs('admin.servicios.*') || request()->routeIs('admin.empresas.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                Datos
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="datosOpen" x-transition
                                 class="absolute left-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                                <a href="{{ route('admin.clientes.index') }}"
                                   class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('admin.clientes.*') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Clientes
                                </a>
                                <a href="{{ route('admin.servicios.index') }}"
                                   class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('admin.servicios.*') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Conceptos
                                </a>
                                <a href="{{ route('admin.empresas.index') }}"
                                   class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('admin.empresas.*') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Empresas
                                </a>
                            </div>
                        </div>

                        @endif

                        {{-- Admin --}}
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Panel
                        </a>
                        <a href="{{ route('admin.presupuestos.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.presupuestos.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Presupuestos
                        </a>
                        @if(auth()->user()->company?->canUseNotas())
                        <a href="{{ route('admin.notas.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.notas.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                             Notas
                        </a>
                        @endif
                        {{-- Datos dropdown (admin) --}}
                        <div x-data="{ datosOpen: false }" class="relative">
                            <button @click="datosOpen = !datosOpen" @click.outside="datosOpen = false"
                                    class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium transition
                                           {{ request()->routeIs('admin.clientes.*') || request()->routeIs('admin.servicios.*') || request()->routeIs('admin.empresas.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                Datos
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="datosOpen" x-transition
                                 class="absolute left-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                                <a href="{{ route('admin.clientes.index') }}"
                                   class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('admin.clientes.*') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Clientes
                                </a>
                                <a href="{{ route('admin.servicios.index') }}"
                                   class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('admin.servicios.*') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Conceptos
                                </a>
                                <a href="{{ route('admin.empresas.index') }}"
                                   class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('admin.empresas.*') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                                    Empresas
                                </a>
                            </div>
                        </div>

                        <a href="{{ route('admin.users.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Usuarios
                        </a>
                        @endif

                        {{-- Superadmin --}}
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('superadmin.dashboard') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('superadmin.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Panel
                        </a>
                        <a href="{{ route('superadmin.companies.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('superadmin.companies.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Cuentas
                        </a>
                        <a href="{{ route('superadmin.users.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('superadmin.users.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Usuarios
                        </a>
                        <a href="{{ route('superadmin.plans.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('superadmin.plans.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Planes
                        </a>
                        <a href="{{ route('superadmin.noticias.index') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('superadmin.noticias.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Noticias
                        </a>


<div x-data="{ analyticsOpen: false }" class="relative">
    <button @click="analyticsOpen = !analyticsOpen" @click.outside="analyticsOpen = false"
            class="flex items-center gap-1 px-3 py-2 rounded-lg text-sm font-medium transition
                   {{ request()->routeIs('superadmin.analytics.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
        Analytics
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="analyticsOpen" x-transition
         class="absolute left-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
        <a href="{{ route('superadmin.analytics.index') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('superadmin.analytics.index') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
            Resumen
        </a>
        <a href="{{ route('superadmin.analytics.pages') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('superadmin.analytics.pages') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
            Páginas
        </a>
        <a href="{{ route('superadmin.analytics.visits') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('superadmin.analytics.visits') ? 'text-indigo-700 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
            Visitas
        </a>
    </div>
</div>


                        <a href="{{ route('superadmin.settings') }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition
                                  {{ request()->routeIs('superadmin.settings*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Configuración
                        </a>
                        @endif
                    @endauth
                </div>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                @auth
                    {{-- Notification bell --}}
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    <a href="{{ route('notifications.index') }}"
                       class="relative p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-red-500 rounded-full">
                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                        </span>
                        @endif
                    </a>

                    {{-- Role badge --}}
                    <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if(auth()->user()->isSuperAdmin()) bg-purple-100 text-purple-800
                        @elseif(auth()->user()->isAdmin()) bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        @if(auth()->user()->isSuperAdmin()) Superadmin
                        @elseif(auth()->user()->isAdmin()) Admin
                        @else Trabajador @endif
                    </span>

                    {{-- Profile dropdown --}}
                    <div class="relative">
                        <button @click="profileOpen = !profileOpen" @click.outside="profileOpen = false"
                                class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100 transition focus:outline-none">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" class="w-7 h-7 rounded-full object-cover" alt="">
                            @else
                                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="hidden sm:block font-medium max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div x-show="profileOpen" x-transition
                             class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Conectado como</p>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Mi Perfil
                            </a>
                            <a href="{{ route('two-factor.setup') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                2FA
                                @if(auth()->user()->two_factor_enabled)
                                    <span class="ml-auto text-xs text-green-600 font-medium">Activo</span>
                                @endif
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.subscription') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                Suscripción
                            </a>
                            @endif
                            <div class="border-t border-gray-100 mt-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Mobile menu button --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

{{-- Mobile menu fullscreen --}}
<div
    x-show="mobileOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    class="fixed inset-0 z-50 md:hidden bg-slate-950/30 backdrop-blur-sm"
>
    <div class="flex h-full flex-col bg-white">
        {{-- Header --}}
        <div class="border-b border-slate-200 bg-white/95 px-4 py-4 backdrop-blur">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-50 ring-1 ring-indigo-100">
                        <img src="/pwa-icons/icon-192x192.png" class="h-7 w-7" alt="Logo {{ config('app.name') }}">
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ config('app.name') }}</p>
                        <p class="text-xs text-slate-500">Menú de navegación</p>
                    </div>
                </div>

                <button @click="mobileOpen = false"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            @auth
                <div class="mt-4 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" class="h-10 w-10 rounded-full object-cover" alt="">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>

                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold
                        @if(auth()->user()->isSuperAdmin()) bg-purple-100 text-purple-700
                        @elseif(auth()->user()->isAdmin()) bg-blue-100 text-blue-700
                        @else bg-emerald-100 text-emerald-700 @endif">
                        @if(auth()->user()->isSuperAdmin()) Superadmin
                        @elseif(auth()->user()->isAdmin()) Admin
                        @else Trabajador @endif
                    </span>
                </div>
            @endauth
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto px-4 py-4">
            @auth
                <div class="space-y-6">
                    {{-- navegación principal --}}
                    <section>
                        <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Principal
                        </p>

                        <div class="space-y-1">
                            @if(auth()->user()->isWorker())
                                <a href="{{ route('worker.dashboard') }}"
                                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Mis tareas
                                </a>

                                <a href="{{ route('admin.presupuestos.index') }}"
                                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Presupuestos
                                </a>

                                <div x-data="{ datosOpen: false }" class="rounded-2xl border border-slate-200 bg-slate-50/70">
                                    <button @click="datosOpen = !datosOpen"
                                            class="flex w-full items-center justify-between px-4 py-3 text-sm font-medium text-slate-700">
                                        <span>Datos</span>
                                        <svg class="h-4 w-4 text-slate-400 transition" :class="{ 'rotate-180': datosOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="datosOpen" x-transition class="space-y-1 px-2 pb-2">
                                        <a href="{{ route('admin.clientes.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">Clientes</a>
                                        <a href="{{ route('admin.servicios.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">Conceptos</a>
                                        <a href="{{ route('admin.empresas.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">Empresas</a>
                                    </div>
                                </div>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}"
                                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Panel
                                </a>

                                <a href="{{ route('admin.presupuestos.index') }}"
                                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Presupuestos
                                </a>

                                @if(auth()->user()->company?->canUseNotas())
                                    <a href="{{ route('admin.notas.index') }}"
                                       class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                        Notas
                                    </a>
                                @endif

                                <div x-data="{ datosOpen: false }" class="rounded-2xl border border-slate-200 bg-slate-50/70">
                                    <button @click="datosOpen = !datosOpen"
                                            class="flex w-full items-center justify-between px-4 py-3 text-sm font-medium text-slate-700">
                                        <span>Datos</span>
                                        <svg class="h-4 w-4 text-slate-400 transition" :class="{ 'rotate-180': datosOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>

                                    <div x-show="datosOpen" x-transition class="space-y-1 px-2 pb-2">
                                        <a href="{{ route('admin.clientes.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">Clientes</a>
                                        <a href="{{ route('admin.servicios.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">Conceptos</a>
                                        <a href="{{ route('admin.empresas.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">Empresas</a>
                                    </div>
                                </div>

                                <a href="{{ route('admin.users.index') }}"
                                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Usuarios
                                </a>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Panel general</a>
                                <a href="{{ route('superadmin.companies.index') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cuentas</a>
                                <a href="{{ route('superadmin.users.index') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Usuarios</a>
                                <a href="{{ route('superadmin.plans.index') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Planes</a>
                                <a href="{{ route('superadmin.noticias.index') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Noticias</a>
                                <a href="{{ route('superadmin.settings') }}" class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Configuración</a>


<div x-data="{ analyticsOpen: false }" class="rounded-2xl border border-slate-200 bg-slate-50/70">
    <button @click="analyticsOpen = !analyticsOpen"
            class="flex w-full items-center justify-between px-4 py-3 text-sm font-medium text-slate-700">
        <span>Analytics</span>
        <svg class="h-4 w-4 text-slate-400 transition" :class="{ 'rotate-180': analyticsOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="analyticsOpen" x-transition class="space-y-1 px-2 pb-2">
        <a href="{{ route('superadmin.analytics.index') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">
            Resumen
        </a>
        <a href="{{ route('superadmin.analytics.pages') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">
            Páginas
        </a>
        <a href="{{ route('superadmin.analytics.visits') }}" class="block rounded-xl px-3 py-2 text-sm text-slate-600 transition hover:bg-white hover:text-slate-900">
            Visitas
        </a>
    </div>
</div>



                            @endif
                        </div>
                    </section>

                    {{-- cuenta --}}
                    <section>
                        <p class="mb-2 px-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-slate-400">
                            Cuenta
                        </p>

                        <div class="space-y-1">
                            <a href="{{ route('profile.edit') }}"
                               class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                Mi perfil
                            </a>

                            <a href="{{ route('two-factor.setup') }}"
                               class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                Seguridad (2FA)
                            </a>

                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.subscription') }}"
                                   class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                    Suscripción
                                </a>
                            @endif

                            <a href="{{ route('notifications.index') }}"
                               class="flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                <span>Notificaciones</span>
                                @php $unreadMobile = auth()->user()->unreadNotifications->count(); @endphp
                                @if($unreadMobile > 0)
                                    <span class="inline-flex min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 py-0.5 text-[11px] font-bold text-white">
                                        {{ $unreadMobile > 9 ? '9+' : $unreadMobile }}
                                    </span>
                                @endif
                            </a>
                        </div>
                    </section>
                </div>
            @endauth
        </div>

        {{-- footer --}}
        @auth
            <div class="border-t border-slate-200 bg-white px-4 py-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-600 transition hover:bg-rose-100">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        @endauth
    </div>
</div>
</nav>
