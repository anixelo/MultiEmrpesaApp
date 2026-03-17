<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('superadmin.plans.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Nuevo plan</h1>
                <p class="mt-1 truncate text-sm text-slate-500">Crea un nuevo plan de suscripción</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <form method="POST" action="{{ route('superadmin.plans.store') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Nombre del plan <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Ej. Profesional"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('name') border-rose-400 @enderror"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Descripción
                    </label>
                    <textarea
                        name="description"
                        rows="3"
                        placeholder="Descripción breve del plan"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Precio mensual (€) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="price_monthly"
                            value="{{ old('price_monthly', 0) }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('price_monthly') border-rose-400 @enderror"
                        >
                        @error('price_monthly')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Precio anual (€) <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="price_yearly"
                            value="{{ old('price_yearly', 0) }}"
                            min="0"
                            step="0.01"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('price_yearly') border-rose-400 @enderror"
                        >
                        @error('price_yearly')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Máx. usuarios <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="max_users"
                            value="{{ old('max_users', 5) }}"
                            min="1"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('max_users') border-rose-400 @enderror"
                        >
                        @error('max_users')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">
                            Máx. presupuestos/mes
                            <span class="font-normal text-slate-400">(0 = ilimitado)</span>
                        </label>
                        <input
                            type="number"
                            name="max_presupuestos"
                            value="{{ old('max_presupuestos', 0) }}"
                            min="0"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('max_presupuestos') border-rose-400 @enderror"
                        >
                        @error('max_presupuestos')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Máx. empresas activas
                        <span class="font-normal text-slate-400">(0 = ilimitado)</span>
                    </label>
                    <input
                        type="number"
                        name="max_empresas"
                        value="{{ old('max_empresas', 1) }}"
                        min="0"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 @error('max_empresas') border-rose-400 @enderror"
                    >
                    @error('max_empresas')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">
                        Características
                        <span class="font-normal text-slate-400">(una por línea)</span>
                    </label>
                    <textarea
                        name="features"
                        rows="5"
                        placeholder="5 usuarios&#10;10 presupuestos al mes&#10;Soporte por email"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2.5 font-mono text-sm text-slate-700 shadow-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >{{ old('features') }}</textarea>
                </div>

                <div class="space-y-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="has_notes" value="1" {{ old('has_notes') ? 'checked' : '' }} class="peer sr-only">
                            <div class="h-6 w-10 rounded-full bg-slate-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                        <span class="text-sm font-medium text-slate-700">Permite gestión de notas</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="has_plantillas" value="1" {{ old('has_plantillas') ? 'checked' : '' }} class="peer sr-only">
                            <div class="h-6 w-10 rounded-full bg-slate-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                        <span class="text-sm font-medium text-slate-700">Permite gestión de plantillas</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="has_envio_enlace" value="1" {{ old('has_envio_enlace') ? 'checked' : '' }} class="peer sr-only">
                            <div class="h-6 w-10 rounded-full bg-slate-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                        <span class="text-sm font-medium text-slate-700">Envío de presupuesto por enlace (enlace público, email y WhatsApp)</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="has_historial_cambios" value="1" {{ old('has_historial_cambios') ? 'checked' : '' }} class="peer sr-only">
                            <div class="h-6 w-10 rounded-full bg-slate-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                        <span class="text-sm font-medium text-slate-700">Historial de cambios del presupuesto</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="active" value="1" {{ old('active', true) ? 'checked' : '' }} class="peer sr-only">
                            <div class="h-6 w-10 rounded-full bg-slate-200 transition peer-checked:bg-indigo-600 peer-focus:ring-2 peer-focus:ring-indigo-200 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:transition-all after:content-[''] peer-checked:after:translate-x-full peer-checked:after:border-white"></div>
                        </label>
                        <span class="text-sm font-medium text-slate-700">Plan activo</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('superadmin.plans.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Cancelar
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                        Crear plan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>