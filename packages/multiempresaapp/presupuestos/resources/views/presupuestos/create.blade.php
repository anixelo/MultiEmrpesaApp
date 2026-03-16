<x-app-layout>


    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.presupuestos.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Nuevo presupuesto</h1>
                <p class="mt-1 text-sm text-slate-500">Crea un nuevo presupuesto para tu empresa</p>
            </div>
        </div>
    </x-slot>



    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <livewire:presupuestos.presupuesto-form :nota-id="$notaId ?? null" :plantilla-id="$plantillaId ?? null" />
            </div>
        </div>
    </div>
</x-app-layout>