<?php

namespace MultiempresaApp\Servicios\Livewire;

use Livewire\Component;
use MultiempresaApp\Servicios\Models\Servicio;

class ServicioSelector extends Component
{
    public string $search = '';
    public ?int $selectedServicioId = null;
    public string $selectedServicioNombre = '';
    public bool $showDropdown = false;

    public array $servicios = [];

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            $empresaId = auth()->user()->company_id;

            $this->servicios = Servicio::deEmpresa($empresaId)
                ->activos()
                ->where('nombre', 'like', "%{$this->search}%")
                ->limit(10)
                ->get()
                ->toArray();

            $this->showDropdown = true;
        } else {
            $this->servicios = [];
            $this->showDropdown = false;
        }
    }

    public function selectServicio(int $id, string $nombre, string $precio, ?string $ivaTipo): void
    {
        $this->selectedServicioId = $id;
        $this->selectedServicioNombre = $nombre;
        $this->showDropdown = false;
        $this->search = '';

        $this->dispatch('servicioSeleccionado',
            servicioId: $id,
            nombre: $nombre,
            precio: $precio,
            ivaTipo: $ivaTipo
        );
    }

    public function clearSelection(): void
    {
        $this->selectedServicioId = null;
        $this->selectedServicioNombre = '';
    }

    public function render()
    {
        return view('servicios::livewire.servicio-selector', [
            'servicios' => $this->servicios,
        ]);
    }
}
