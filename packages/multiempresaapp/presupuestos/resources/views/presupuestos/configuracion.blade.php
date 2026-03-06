<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Configuración de Presupuestos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded-md bg-green-50 p-4">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow sm:rounded-lg">
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('admin.presupuestos.configuracion.update') }}">
                        @csrf

                        {{-- IVA por defecto --}}
                        <div class="mb-4">
                            <label for="iva_defecto" class="block text-sm font-medium text-gray-700">
                                IVA por defecto <span class="text-red-500">*</span>
                            </label>
                            <select id="iva_defecto"
                                    name="iva_defecto"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('iva_defecto') border-red-300 @enderror">
                                <option value="0"  {{ (string)$config->iva_defecto === '0.00' || (string)$config->iva_defecto === '0' ? 'selected' : '' }}>0%</option>
                                <option value="4"  {{ (string)$config->iva_defecto === '4.00' || (string)$config->iva_defecto === '4' ? 'selected' : '' }}>4%</option>
                                <option value="10" {{ (string)$config->iva_defecto === '10.00' || (string)$config->iva_defecto === '10' ? 'selected' : '' }}>10%</option>
                                <option value="21" {{ (string)$config->iva_defecto === '21.00' || (string)$config->iva_defecto === '21' ? 'selected' : '' }}>21%</option>
                            </select>
                            @error('iva_defecto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prefijo --}}
                        <div class="mb-4">
                            <label for="prefijo" class="block text-sm font-medium text-gray-700">
                                Prefijo del número
                            </label>
                            <input type="text"
                                   id="prefijo"
                                   name="prefijo"
                                   value="{{ old('prefijo', $config->prefijo) }}"
                                   maxlength="20"
                                   placeholder="Ej: PRE"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('prefijo') border-red-300 @enderror">
                            @error('prefijo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Validez días --}}
                        <div class="mb-4">
                            <label for="validez_dias" class="block text-sm font-medium text-gray-700">
                                Validez por defecto (días)
                            </label>
                            <input type="number"
                                   id="validez_dias"
                                   name="validez_dias"
                                   value="{{ old('validez_dias', $config->validez_dias) }}"
                                   min="1"
                                   placeholder="30"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('validez_dias') border-red-300 @enderror">
                            @error('validez_dias')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Forma de pago por defecto --}}
                        <div class="mb-4">
                            <label for="forma_pago_defecto" class="block text-sm font-medium text-gray-700">
                                Forma de pago por defecto
                            </label>
                            <input type="text"
                                   id="forma_pago_defecto"
                                   name="forma_pago_defecto"
                                   value="{{ old('forma_pago_defecto', $config->forma_pago_defecto) }}"
                                   maxlength="255"
                                   placeholder="Ej: Transferencia bancaria"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('forma_pago_defecto') border-red-300 @enderror">
                            @error('forma_pago_defecto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Observaciones por defecto --}}
                        <div class="mb-6">
                            <label for="observaciones_defecto" class="block text-sm font-medium text-gray-700">
                                Observaciones por defecto
                            </label>
                            <textarea id="observaciones_defecto"
                                      name="observaciones_defecto"
                                      rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('observaciones_defecto') border-red-300 @enderror">{{ old('observaciones_defecto', $config->observaciones_defecto) }}</textarea>
                            @error('observaciones_defecto')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Guardar configuración
                            </button>
                            <a href="{{ route('admin.presupuestos.index') }}"
                               class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
