<x-app-layout>


    <x-slot name="header">
        <div class="flex items-start gap-3 sm:items-center">
            <a href="{{ route('admin.users.index') }}"
               class="mt-0.5 inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-400 shadow-sm transition hover:bg-slate-50 hover:text-slate-600 sm:mt-0">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>

            <div class="min-w-0">
                <h1 class="text-2xl font-bold text-slate-900">Nuevo usuario</h1>
                <p class="mt-0.5 text-sm text-slate-500">Añade un nuevo miembro a tu empresa</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl py-8 px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Nombre <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full rounded-2xl border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-rose-400 @enderror"
                           placeholder="Nombre completo">
                    @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full rounded-2xl border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-rose-400 @enderror"
                           placeholder="correo@empresa.com">
                    @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div x-data="{ role: '{{ old('role', 'trabajador') }}' }">
                    <label class="mb-1 block text-sm font-medium text-slate-700">Rol <span class="text-rose-500">*</span></label>
                    <select name="role" x-model="role"
                            class="w-full rounded-2xl border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('role') border-rose-400 @enderror">
                        <option value="trabajador">Trabajador</option>
                        <option value="administrador">Administrador</option>
                    </select>
                    @error('role')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror

                    <div x-show="role === 'trabajador'" x-transition class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                        <label class="flex cursor-pointer items-start gap-3">
                            <input type="checkbox" name="revisar_presupuestos" value="1"
                                   {{ old('revisar_presupuestos') ? 'checked' : '' }}
                                   class="mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                            <span>
                                <span class="block text-sm font-medium text-slate-700">Revisar presupuestos</span>
                                <span class="mt-0.5 block text-xs text-slate-500">El administrador deberá validar los presupuestos antes de que el trabajador pueda descargarlos o enviarlos al cliente.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Contraseña <span class="text-rose-500">*</span></label>
                    <input type="password" name="password"
                           class="w-full rounded-2xl border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-rose-400 @enderror">
                    @error('password')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Confirmar contraseña <span class="text-rose-500">*</span></label>
                    <input type="password" name="password_confirmation"
                           class="w-full rounded-2xl border border-slate-300 px-3 py-2.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-3">
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-2xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="rounded-2xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                        Crear usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>