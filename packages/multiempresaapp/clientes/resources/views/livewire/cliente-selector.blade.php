<div class="relative" x-data="{ open: @entangle('showDropdown') }">
    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>

    <input type="hidden" wire:model="selectedClienteId" name="cliente_id">

    @if ($selectedClienteNombre)
        <div class="flex items-center gap-2 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900">
            <span class="flex-1">{{ $selectedClienteNombre }}</span>
            <button type="button"
                    wire:click="clearSelection"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none"
                    title="Quitar selección">
                &times;
            </button>
        </div>
    @else
        <input type="text"
               wire:model.live="search"
               placeholder="Buscar cliente..."
               autocomplete="off"
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

        @if ($showDropdown)
            <div class="absolute z-10 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg">
                @if (count($clientes) > 0)
                    <ul class="max-h-60 overflow-auto py-1">
                        @foreach ($clientes as $cliente)
                            <li>
                                <button type="button"
                                        wire:click="selectCliente({{ $cliente['id'] }}, '{{ addslashes($cliente['nombre']) }}')"
                                        class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    <span class="font-medium">{{ $cliente['nombre'] }}</span>
                                    @if ($cliente['email'])
                                        <span class="ml-2 text-gray-400">{{ $cliente['email'] }}</span>
                                    @endif
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="px-4 py-2 text-sm text-gray-500">No se encontraron clientes.</p>
                @endif
                <div class="border-t border-gray-100">
                    <button type="button"
                            wire:click="openModal"
                            class="w-full px-4 py-2 text-left text-sm text-indigo-600 hover:bg-indigo-50">
                        + Crear nuevo cliente
                    </button>
                </div>
            </div>
        @endif
    @endif

    <div class="mt-2">
        <button type="button"
                wire:click="openModal"
                class="text-sm text-indigo-600 hover:text-indigo-900 underline">
            + Crear nuevo cliente
        </button>
    </div>

    {{-- Modal de creación rápida --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
                <h3 class="mb-4 text-lg font-medium text-gray-900">Nuevo Cliente</h3>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">
                        Nombre <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           wire:model="quickNombre"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('quickNombre') border-red-300 @enderror">
                    @error('quickNombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email"
                           wire:model="quickEmail"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('quickEmail') border-red-300 @enderror">
                    @error('quickEmail')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text"
                           wire:model="quickTelefono"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button"
                            wire:click="closeModal"
                            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Cancelar
                    </button>
                    <button type="button"
                            wire:click="quickCreate"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Crear
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
