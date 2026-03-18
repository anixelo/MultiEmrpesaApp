<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between gap-3 sm:items-center">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold text-slate-900">Mensajes de contacto</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Formularios de contacto recibidos del sitio web</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-base font-semibold text-slate-900">Listado de mensajes</h2>
                <span class="text-xs text-slate-400">{{ $messages->total() }} resultado(s)</span>
            </div>

            <div class="hidden md:block">
                <div class="overflow-x-auto px-4 pb-4 pt-2 sm:px-6">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Remitente</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Asunto</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Estado</th>
                                <th class="px-4 py-2 text-left text-[11px] font-semibold tracking-[0.08em] text-slate-400">Fecha</th>
                                <th class="px-4 py-2 text-right text-[11px] font-semibold tracking-[0.08em] text-slate-400">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($messages as $message)
                                <tr class="rounded-2xl bg-slate-50 shadow-sm ring-1 ring-slate-200 transition hover:bg-white hover:shadow-md hover:ring-indigo-100 {{ !$message->read_at ? 'font-semibold' : '' }}">
                                    <td class="rounded-l-2xl px-4 py-4">
                                        <p class="text-sm font-semibold text-slate-900">{{ $message->name }}</p>
                                        <p class="mt-0.5 text-xs text-slate-400">{{ $message->email }}</p>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-700">
                                        {{ $message->subject }}
                                    </td>

                                    <td class="px-4 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $message->read_at ? 'bg-slate-100 text-slate-600' : 'bg-indigo-100 text-indigo-700' }}">
                                            {{ $message->read_at ? 'Leído' : 'Nuevo' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-4 text-sm text-slate-500">
                                        {{ $message->created_at->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="rounded-r-2xl px-4 py-4 text-right">
                                        <div class="flex flex-wrap items-center justify-end gap-2">
                                            <a href="{{ route('superadmin.contact-messages.show', $message) }}"
                                               class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                                Ver
                                            </a>

                                            <form method="POST" action="{{ route('superadmin.contact-messages.destroy', $message) }}"
                                                  onsubmit="return confirm('¿Eliminar este mensaje?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-sm text-slate-400">
                                        No hay mensajes de contacto aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="space-y-3 p-4 md:hidden">
                @forelse($messages as $message)
                    <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $message->name }}</p>
                                <p class="mt-0.5 text-xs text-slate-400">{{ $message->email }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $message->subject }}</p>
                            </div>

                            <span class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-medium {{ $message->read_at ? 'bg-slate-100 text-slate-600' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ $message->read_at ? 'Leído' : 'Nuevo' }}
                            </span>
                        </div>

                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 pt-3">
                            <span class="text-xs text-slate-400">{{ $message->created_at->format('d/m/Y H:i') }}</span>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('superadmin.contact-messages.show', $message) }}"
                                   class="inline-flex items-center rounded-xl bg-indigo-50 px-3 py-2 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100">
                                    Ver
                                </a>

                                <form method="POST" action="{{ route('superadmin.contact-messages.destroy', $message) }}"
                                      onsubmit="return confirm('¿Eliminar este mensaje?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center rounded-xl bg-rose-50 px-3 py-2 text-xs font-medium text-rose-700 transition hover:bg-rose-100">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center text-sm text-slate-400 shadow-sm">
                        No hay mensajes de contacto aún.
                    </div>
                @endforelse
            </div>

            @if($messages->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $messages->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
