<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.companies.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Empresa</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $company->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-5">
            <form method="POST" action="{{ route('superadmin.companies.update', $company) }}" class="space-y-5">
                @csrf @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                           class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-300 @enderror">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $company->email) }}"
                               class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-300 @enderror">
                        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                        <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                               class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <textarea name="address" rows="2"
                              class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('address', $company->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan de suscripción</label>
                    <select name="plan_id" class="w-full border-gray-300 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">— Sin plan —</option>
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}" {{ old('plan_id', $currentPlanId) == $plan->id ? 'selected' : '' }}>
                            {{ $plan->name }} — {{ $plan->isFree() ? 'Gratis' : '€'.number_format($plan->price_monthly,2).'/mes' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="active" id="active" value="1" {{ old('active', $company->active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="active" class="text-sm font-medium text-gray-700">Empresa activa</label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('superadmin.companies.index') }}"
                       class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition">Cancelar</a>
                    <button type="submit"
                            class="px-4 py-2 text-sm text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition shadow-sm">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
