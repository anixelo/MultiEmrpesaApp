<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('superadmin.contact-messages.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Mensaje de contacto</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Detalle del mensaje recibido</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-base font-semibold text-slate-900">{{ $contactMessage->subject }}</h2>
                    <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-medium {{ $contactMessage->read_at ? 'bg-slate-100 text-slate-600' : 'bg-indigo-100 text-indigo-700' }}">
                        {{ $contactMessage->read_at ? 'Leído' : 'Nuevo' }}
                    </span>
                </div>
            </div>

            <div class="divide-y divide-slate-100 px-6">
                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:gap-4">
                    <span class="w-28 shrink-0 text-sm font-medium text-slate-500">Nombre</span>
                    <span class="text-sm text-slate-900">{{ $contactMessage->name }}</span>
                </div>

                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:gap-4">
                    <span class="w-28 shrink-0 text-sm font-medium text-slate-500">Email</span>
                    <a href="mailto:{{ $contactMessage->email }}"
                       class="text-sm text-indigo-600 hover:underline">{{ $contactMessage->email }}</a>
                </div>

                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:gap-4">
                    <span class="w-28 shrink-0 text-sm font-medium text-slate-500">Asunto</span>
                    <span class="text-sm text-slate-900">{{ $contactMessage->subject }}</span>
                </div>

                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:gap-4">
                    <span class="w-28 shrink-0 text-sm font-medium text-slate-500">Fecha</span>
                    <span class="text-sm text-slate-900">{{ $contactMessage->created_at->format('d/m/Y H:i') }}</span>
                </div>

                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:gap-4">
                    <span class="w-28 shrink-0 text-sm font-medium text-slate-500">Mensaje</span>
                    <div class="text-sm text-slate-900 whitespace-pre-wrap">{{ $contactMessage->message }}</div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 border-t border-slate-100 px-6 py-5">
                <a href="mailto:{{ $contactMessage->email }}"
                   class="inline-flex items-center gap-2 rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Responder por email
                </a>

                <form method="POST" action="{{ route('superadmin.contact-messages.destroy', $contactMessage) }}"
                      onsubmit="return confirm('¿Eliminar este mensaje?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-2xl bg-rose-50 px-4 py-2.5 text-sm font-medium text-rose-700 shadow-sm transition hover:bg-rose-100">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar
                    </button>
                </form>

                <a href="{{ route('superadmin.contact-messages.index') }}"
                   class="inline-flex items-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Volver
                </a>
            </div>
        </section>
    </div>
</x-app-layout>
