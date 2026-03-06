<?php

namespace MultiempresaApp\Clientes\Livewire;

use Livewire\Component;
use MultiempresaApp\Clientes\Models\Cliente;

class ClienteSelector extends Component
{
    public string $search = '';
    public ?int $selectedClienteId = null;
    public string $selectedClienteNombre = '';
    public bool $showDropdown = false;
    public bool $showModal = false;
    public string $quickNombre = '';
    public string $quickEmail = '';
    public string $quickTelefono = '';

    public array $clientes = [];

    public function mount(?int $clienteId = null, string $clienteNombre = ''): void
    {
        if ($clienteId) {
            $this->selectedClienteId    = $clienteId;
            $this->selectedClienteNombre = $clienteNombre;
        }
    }

    public function updatedSearch(): void
    {
        if (strlen($this->search) >= 2) {
            $empresaId = auth()->user()->company_id;

            $this->clientes = Cliente::deEmpresa($empresaId)
                ->where(function ($q) {
                    $q->where('nombre', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%")
                      ->orWhere('telefono', 'like', "%{$this->search}%");
                })
                ->limit(10)
                ->get()
                ->toArray();

            $this->showDropdown = true;
        } else {
            $this->clientes = [];
            $this->showDropdown = false;
        }
    }

    public function selectCliente(int $id, string $nombre): void
    {
        $this->selectedClienteId = $id;
        $this->selectedClienteNombre = $nombre;
        $this->showDropdown = false;
        $this->search = '';

        $this->dispatch('clienteSeleccionado', clienteId: $id, nombre: $nombre);
    }

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->quickNombre = '';
        $this->quickEmail = '';
        $this->quickTelefono = '';
    }

    public function quickCreate(): void
    {
        $this->validate([
            'quickNombre'   => 'required',
            'quickEmail'    => 'nullable|email',
            'quickTelefono' => 'nullable',
        ]);

        $cliente = Cliente::create([
            'empresa_id' => auth()->user()->company_id,
            'nombre'     => $this->quickNombre,
            'email'      => $this->quickEmail ?: null,
            'telefono'   => $this->quickTelefono ?: null,
        ]);

        $this->showModal = false;
        $this->quickNombre = '';
        $this->quickEmail = '';
        $this->quickTelefono = '';

        $this->selectCliente($cliente->id, $cliente->nombre);
    }

    public function clearSelection(): void
    {
        $this->selectedClienteId = null;
        $this->selectedClienteNombre = '';
    }

    public function render()
    {
        return view('clientes::livewire.cliente-selector', [
            'clientes' => $this->clientes,
        ]);
    }
}
