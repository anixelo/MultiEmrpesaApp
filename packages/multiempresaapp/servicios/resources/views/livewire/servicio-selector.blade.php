<div class="relative">
    <label class="mb-1 block text-sm font-medium text-slate-700">Servicio</label>

    <input type="hidden" wire:model="selectedServicioId" name="servicio_id">

    @if ($selectedServicioNombre)
        <div class="flex items-center gap-2 rounded-2xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 shadow-sm">
            <span class="flex-1 truncate">{{ $selectedServicioNombre }}</span>
            <button type="button"
                    wire:click="clearSelection"
                    class="text-slate-400 transition hover:text-slate-600 focus:outline-none"
                    title="Quitar selección">
                &times;
            </button>
        </div>
    @else
        <input type="text"
               wire:model.live="search"
               placeholder="Buscar servicio..."
               autocomplete="off"
               class="block w-full rounded-2xl border-slate-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">

        @if ($showDropdown)
            <div class="absolute z-10 mt-2 w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
                @if (count($servicios) > 0)
                    <ul class="max-h-60 overflow-auto py-1">
                        @foreach ($servicios as $servicio)
                            <li>
                                <button type="button"
                                        wire:click="selectServicio({{ $servicio['id'] }}, '{{ addslashes($servicio['nombre']) }}', '{{ $servicio['precio'] }}', '{{ $servicio['iva_tipo'] }}')"
                                        class="w-full px-4 py-3 text-left text-sm text-slate-700 transition hover:bg-indigo-50 hover:text-indigo-700">
                                    <span class="font-medium">{{ $servicio['nombre'] }}</span>
                                    <span class="ml-2 text-slate-400">
                                        {{ number_format($servicio['precio'], 2, ',', '.') }} €
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="px-4 py-3 text-sm text-slate-500">No se encontraron servicios.</p>
                @endif
            </div>
        @endif
    @endif
</div>