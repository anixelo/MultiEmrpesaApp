<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.users.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Usuario</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $user->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('superadmin.users.update', $user) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror">
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nueva contraseña <span class="text-gray-400 font-normal">(dejar vacío para no cambiar)</span></label>
                        <input type="password" name="password"
                               class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-300 @enderror">
                        @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation"
                               class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                        <select name="role" required
                                class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('role') border-red-300 @enderror">
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ (old('role', $userRole) === $role->name) ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                        <select name="company_id"
                                class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sin empresa</option>
                            @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="text-xs text-gray-400">
                        2FA: <span class="{{ $user->two_factor_enabled ? 'text-green-600 font-medium' : 'text-gray-400' }}">{{ $user->two_factor_enabled ? 'Activo' : 'Inactivo' }}</span>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('superadmin.users.index') }}"
                           class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">Cancelar</a>
                        <button type="submit"
                                class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">Guardar cambios</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
