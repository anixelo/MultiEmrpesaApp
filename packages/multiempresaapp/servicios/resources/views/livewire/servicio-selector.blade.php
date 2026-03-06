<div class="relative">
    <label class="block text-sm font-medium text-gray-700 mb-1">Servicio</label>

    <input type="hidden" wire:model="selectedServicioId" name="servicio_id">

    @if ($selectedServicioNombre)
        <div class="flex items-center gap-2 rounded-md border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900">
            <span class="flex-1">{{ $selectedServicioNombre }}</span>
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
               placeholder="Buscar servicio..."
               autocomplete="off"
               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">

        @if ($showDropdown)
            <div class="absolute z-10 mt-1 w-full rounded-md border border-gray-200 bg-white shadow-lg">
                @if (count($servicios) > 0)
                    <ul class="max-h-60 overflow-auto py-1">
                        @foreach ($servicios as $servicio)
                            <li>
                                <button type="button"
                                        wire:click="selectServicio({{ $servicio['id'] }}, '{{ addslashes($servicio['nombre']) }}', '{{ $servicio['precio'] }}', '{{ $servicio['iva_tipo'] }}')"
                                        class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    <span class="font-medium">{{ $servicio['nombre'] }}</span>
                                    <span class="ml-2 text-gray-400">
                                        {{ number_format($servicio['precio'], 2, ',', '.') }} €
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="px-4 py-2 text-sm text-gray-500">No se encontraron servicios.</p>
                @endif
            </div>
        @endif
    @endif
</div>
