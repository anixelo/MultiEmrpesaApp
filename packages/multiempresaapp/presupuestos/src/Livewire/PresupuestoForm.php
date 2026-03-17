<?php

namespace MultiempresaApp\Presupuestos\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Empresa;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
use MultiempresaApp\Presupuestos\Models\PresupuestoConfiguracion;
use MultiempresaApp\Presupuestos\Services\PresupuestoCalculator;
use MultiempresaApp\PlantillasPresupuesto\Models\PlantillaPresupuesto;
use MultiempresaApp\PlantillasPresupuesto\Models\PlantillaPresupuestoLinea;

class PresupuestoForm extends Component
{
    public ?int $presupuestoId = null;
    public ?int $notaId = null;
    public string $notaTitulo = '';
    public string $notaContenido = '';
    public bool $showNotaPanel = true;
    public ?int $clienteId = null;
    public string $clienteNombre = '';
    public ?int $negocioId = null;
    public array $empresasDisponibles = [];
    public string $fecha = '';
    public string $validezHasta = '';
    public string $formaPago = '';
    public string $observaciones = '';
    public string $notas = '';
    public array $lineas = [];
    public float $subtotalBruto = 0;
    public float $subtotalDescuentos = 0;
    public float $totalBaseImponible = 0;
    public float $totalIva = 0;
    public float $total = 0;
    public float $ivaDefecto = 21;

    public array $lineaSearch = [];
    public array $lineaDropdownVisible = [];
    public array $lineaSearchResults = [];
    public bool $showServicioModal = false;
    public int $servicioModalLineaIndex = -1;
    public string $quickServicioNombre = '';
    public string $quickServicioPrecio = '0';
    public string $quickServicioIva = '';
    public bool $showServicioSearchModal = false;
    public int $servicioSearchModalLineaIndex = -1;
    public string $servicioSearchModalQuery = '';
    public array $servicioSearchModalResults = [];

    // Plantilla modal
    public bool $showPlantillaModal = false;
    public string $plantillaNombre = '';
    public string $plantillaSuccess = '';
    public bool $canUsePlantillas = false;

    // Select-plantilla modal (step 1)
    public bool $showSelectPlantillaModal = false;
    public string $selectPlantillaQuery = '';
    public array $selectPlantillaResults = [];
    public ?int $plantillaIdAplicada = null;

    // Wizard step (only used for create mode)
    public int $step = 1;

    public function mount(?int $presupuestoId = null, ?int $notaId = null, ?int $plantillaId = null): void
    {
        $empresaId = auth()->user()->company_id;
        $config    = PresupuestoConfiguracion::getOrCreateForEmpresa($empresaId);

        $this->ivaDefecto = (float) $config->iva_defecto;
        $this->fecha      = now()->format('Y-m-d');

        if ($config->validez_dias) {
            $this->validezHasta = now()->addDays($config->validez_dias)->format('Y-m-d');
        }

        $this->formaPago    = $config->forma_pago_defecto ?? '';
        $this->observaciones = $config->observaciones_defecto ?? '';

        // Check plan permissions
        $company = auth()->user()->company;
        $this->canUsePlantillas = $company ? $company->canUsePlantillas() : false;

        // Load available empresas for this company
        $empresas = Empresa::where('company_id', $empresaId)->where('active', true)->orderBy('name')->get();
        $this->empresasDisponibles = $empresas->map(fn ($e) => ['id' => $e->id, 'name' => $e->name])->toArray();

        // Auto-select if only one empresa
        if (count($this->empresasDisponibles) === 1) {
            $this->negocioId = $this->empresasDisponibles[0]['id'];
        }

        // Pre-fill from nota if provided
        if ($notaId) {
            $nota = \MultiempresaApp\Notas\Models\Nota::with('cliente')
                ->where('empresa_id', $empresaId)
                ->find($notaId);

            if ($nota) {
                $this->notaId        = $nota->id;
                $this->notaTitulo    = $nota->titulo;
                $this->notaContenido = $nota->contenido ?? '';
                $this->clienteId     = $nota->cliente_id;
                $this->clienteNombre = $nota->cliente?->nombre ?? '';
                // Skip to step 3 (datos generales) since client is pre-selected
                $this->step = 3;
            }
        }

        // Pre-fill from plantilla if provided
        if ($plantillaId) {
            $plantilla = PlantillaPresupuesto::with('lineas')
                ->where('empresa_id', $empresaId)
                ->find($plantillaId);

            if ($plantilla) {
                $this->plantillaIdAplicada = $plantilla->id;
                if ($plantilla->negocio_id) {
                    $this->negocioId = $plantilla->negocio_id;
                }
                $this->formaPago    = $plantilla->forma_pago ?? '';
                $this->observaciones = $plantilla->observaciones ?? '';

                foreach ($plantilla->lineas as $idx => $linea) {
                    $this->lineas[] = [
                        'concepto'        => $linea->concepto,
                        'cantidad'        => (float) $linea->cantidad,
                        'precio_unitario' => (float) $linea->precio_unitario,
                        'descuento_tipo'  => $linea->descuento_tipo ?? '',
                        'descuento_valor' => (float) ($linea->descuento_valor ?? 0),
                        'iva_tipo'        => (float) $linea->iva_tipo,
                        'servicio_id'     => null,
                        'base_imponible'  => 0,
                        'iva_cuota'       => 0,
                        'total'           => 0,
                    ];
                    $this->lineaSearch[$idx] = $linea->concepto;
                    $this->lineaDropdownVisible[$idx] = false;
                    $this->lineaSearchResults[$idx] = [];
                }

                $this->recalcularTotales();
                // Go to step 2 (cliente) since empresa is already selected
                if ($this->negocioId) {
                    $this->step = 2;
                }
            }
        }

        if ($presupuestoId) {
            $this->presupuestoId = $presupuestoId;
            $presupuesto = Presupuesto::with('lineas')->findOrFail($presupuestoId);

            $this->clienteId    = $presupuesto->cliente_id;
            $this->clienteNombre = $presupuesto->cliente?->nombre ?? '';
            $this->negocioId    = $presupuesto->negocio_id;
            $this->fecha        = $presupuesto->fecha->format('Y-m-d');
            $this->validezHasta = $presupuesto->validez_hasta?->format('Y-m-d') ?? '';
            $this->formaPago    = $presupuesto->forma_pago ?? '';
            $this->observaciones = $presupuesto->observaciones ?? '';
            $this->notas        = $presupuesto->notas ?? '';

            // Load nota linked to this presupuesto (if created from a nota)
            $nota = \MultiempresaApp\Notas\Models\Nota::where('presupuesto_id', $presupuestoId)
                ->where('empresa_id', $empresaId)
                ->first();

            if ($nota) {
                $this->notaId        = $nota->id;
                $this->notaTitulo    = $nota->titulo;
                $this->notaContenido = $nota->contenido ?? '';
                $this->showNotaPanel = false; // collapsed by default when editing
            }

            foreach ($presupuesto->lineas as $idx => $linea) {
                $this->lineas[] = [
                    'concepto'        => $linea->concepto,
                    'cantidad'        => (float) $linea->cantidad,
                    'precio_unitario' => (float) $linea->precio_unitario,
                    'descuento_tipo'  => $linea->descuento_tipo ?? '',
                    'descuento_valor' => (float) ($linea->descuento_valor ?? 0),
                    'iva_tipo'        => (float) $linea->iva_tipo,
                    'servicio_id'     => $linea->servicio_id,
                    'base_imponible'  => (float) $linea->base_imponible,
                    'iva_cuota'       => (float) $linea->iva_cuota,
                    'total'           => (float) $linea->total,
                ];
                $this->lineaSearch[$idx] = $linea->servicio_id ? '' : $linea->concepto;
                $this->lineaDropdownVisible[$idx] = false;
                $this->lineaSearchResults[$idx] = [];
            }

            $this->recalcularTotales();
        }

        if (empty($this->lineas)) {
            $this->addLinea();
        }
    }

    public function toggleNotaPanel(): void
    {
        $this->showNotaPanel = ! $this->showNotaPanel;
    }

    public function nextStep(): void
    {
        if ($this->step === 2) {
            $this->validate([
                'clienteId' => 'required|integer',
            ], [
                'clienteId.required' => 'Debes seleccionar un cliente antes de continuar.',
            ]);
        }

        if ($this->step === 3) {
            $this->validate([
                'fecha' => 'required|date',
            ], [
                'fecha.required' => 'La fecha es obligatoria.',
            ]);
        }

        if ($this->step < 4) {
            $this->step++;
        }
    }

    public function prevStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function addLinea(): void
    {
        $this->lineas[] = [
            'concepto'        => '',
            'cantidad'        => 1,
            'precio_unitario' => 0,
            'descuento_tipo'  => '',
            'descuento_valor' => 0,
            'iva_tipo'        => $this->ivaDefecto,
            'servicio_id'     => null,
            'base_imponible'  => 0,
            'iva_cuota'       => 0,
            'total'           => 0,
        ];
        $newIndex = count($this->lineas) - 1;
        $this->lineaSearch[$newIndex] = '';
        $this->lineaDropdownVisible[$newIndex] = false;
        $this->lineaSearchResults[$newIndex] = [];
    }

    public function removeLinea(int $index): void
    {
        array_splice($this->lineas, $index, 1);
        $this->recalcularTotales();
    }

    public function searchServicioForLinea(int $index, string $query): void
    {
        $this->lineaSearch[$index] = $query;
        if (strlen($query) >= 2) {
            $empresaId = auth()->user()->company_id;
            $results = \MultiempresaApp\Servicios\Models\Servicio::deEmpresa($empresaId)
                ->activos()
                ->where('nombre', 'like', "%{$query}%")
                ->limit(8)
                ->get()
                ->toArray();
            $this->lineaSearchResults[$index] = $results;
            $this->lineaDropdownVisible[$index] = true;
        } else {
            $this->lineaSearchResults[$index] = [];
            $this->lineaDropdownVisible[$index] = false;
        }
    }

    public function selectServicioForLinea(int $index, int $servicioId, string $nombre, float $precio, ?float $ivaTipo): void
    {
        $this->lineas[$index]['concepto']        = $nombre;
        $this->lineas[$index]['precio_unitario'] = $precio;
        $this->lineas[$index]['iva_tipo']        = $ivaTipo ?? $this->ivaDefecto;
        $this->lineas[$index]['servicio_id']     = $servicioId;
        $this->lineaSearch[$index]           = '';
        $this->lineaDropdownVisible[$index]  = false;
        $this->lineaSearchResults[$index]    = [];
        $this->recalcularTotales();
    }

    public function copySearchToConcepto(int $index): void
    {
        if (empty($this->lineas[$index]['servicio_id'])) {
            $this->lineas[$index]['concepto'] = $this->lineaSearch[$index] ?? '';
            $this->lineaDropdownVisible[$index] = false;
        }
    }

    public function openServicioModal(int $index): void
    {
        $this->servicioModalLineaIndex = $index;
        $this->showServicioModal = true;
        $this->showServicioSearchModal = false;
        $this->quickServicioNombre = $this->lineaSearch[$index] ?? '';
        $this->quickServicioPrecio = '0';
        $this->quickServicioIva = (string) $this->ivaDefecto;
    }

    public function closeServicioModal(): void
    {
        $this->showServicioModal = false;
        $this->servicioModalLineaIndex = -1;
        $this->quickServicioNombre = '';
        $this->quickServicioPrecio = '0';
        $this->quickServicioIva = '';
    }

    public function openServicioSearchModal(int $index): void
    {
        $this->servicioSearchModalLineaIndex = $index;
        $this->showServicioSearchModal = true;
        $this->servicioSearchModalQuery = '';
        $this->servicioSearchModalResults = [];
    }

    public function closeServicioSearchModal(): void
    {
        $this->showServicioSearchModal = false;
        $this->servicioSearchModalLineaIndex = -1;
        $this->servicioSearchModalQuery = '';
        $this->servicioSearchModalResults = [];
    }

    public function updatedServicioSearchModalQuery(): void
    {
        if (strlen($this->servicioSearchModalQuery) >= 1) {
            $empresaId = auth()->user()->company_id;
            $this->servicioSearchModalResults = \MultiempresaApp\Servicios\Models\Servicio::deEmpresa($empresaId)
                ->activos()
                ->where('nombre', 'like', "%{$this->servicioSearchModalQuery}%")
                ->limit(20)
                ->get()
                ->toArray();
        } else {
            $this->servicioSearchModalResults = [];
        }
    }

    public function selectServicioFromModal(int $index, int $servicioId, string $nombre, float $precio, ?float $ivaTipo): void
    {
        $this->closeServicioSearchModal();
        $this->selectServicioForLinea($index, $servicioId, $nombre, $precio, $ivaTipo);
    }

    public function openCreateFromServicioSearch(): void
    {
        $index = $this->servicioSearchModalLineaIndex;
        $this->closeServicioSearchModal();
        $this->openServicioModal($index);
    }

    public function quickCreateServicio(): void
    {
        $this->validate([
            'quickServicioNombre' => 'required|string|max:255',
            'quickServicioPrecio' => 'required|numeric|min:0',
            'quickServicioIva'    => 'nullable|numeric|in:0,4,10,21',
        ]);

        $servicio = \MultiempresaApp\Servicios\Models\Servicio::create([
            'empresa_id' => auth()->user()->company_id,
            'nombre'     => $this->quickServicioNombre,
            'precio'     => (float) $this->quickServicioPrecio,
            'iva_tipo'   => $this->quickServicioIva !== '' ? (float) $this->quickServicioIva : null,
            'activo'     => true,
            'created_by' => auth()->id(),
        ]);

        $index = $this->servicioModalLineaIndex;
        $this->closeServicioModal();

        if ($index >= 0) {
            $this->selectServicioForLinea($index, $servicio->id, $servicio->nombre, (float) $servicio->precio, $servicio->iva_tipo !== null ? (float) $servicio->iva_tipo : null);
        }
    }

    public function updatedLineas(): void
    {
        $this->recalcularTotales();
    }

    #[On('servicioSeleccionado')]
    public function servicioSeleccionado(int $servicioId, string $nombre, float $precio, ?float $ivaTipo): void
    {
        $filled = false;
        foreach ($this->lineas as $i => $linea) {
            if (empty($linea['concepto']) && $linea['precio_unitario'] == 0) {
                $this->lineas[$i]['servicio_id']     = $servicioId;
                $this->lineas[$i]['concepto']        = $nombre;
                $this->lineas[$i]['precio_unitario'] = $precio;
                $this->lineas[$i]['iva_tipo']        = $ivaTipo ?? $this->ivaDefecto;
                $filled = true;
                break;
            }
        }

        if (! $filled) {
            $this->lineas[] = [
                'concepto'        => $nombre,
                'cantidad'        => 1,
                'precio_unitario' => $precio,
                'descuento_tipo'  => '',
                'descuento_valor' => 0,
                'iva_tipo'        => $ivaTipo ?? $this->ivaDefecto,
                'servicio_id'     => $servicioId,
                'base_imponible'  => 0,
                'iva_cuota'       => 0,
                'total'           => 0,
            ];
        }

        $this->recalcularTotales();
    }

    #[On('clienteSeleccionado')]
    public function clienteSeleccionado(int $clienteId, string $nombre): void
    {
        $this->clienteId    = $clienteId;
        $this->clienteNombre = $nombre;
    }

    public function recalcularTotales(): void
    {
        $calculator         = new PresupuestoCalculator();
        $subtotalBruto      = 0;
        $subtotalDescuentos = 0;
        $totalBaseImponible = 0;
        $totalIva           = 0;

        foreach ($this->lineas as $i => $linea) {
            $calculated = $calculator->calcularLinea($linea, $this->ivaDefecto);

            $this->lineas[$i]['base_imponible'] = $calculated['base_imponible'];
            $this->lineas[$i]['iva_cuota']      = $calculated['iva_cuota'];
            $this->lineas[$i]['total']          = $calculated['total'];

            $subtotalBruto      += $calculated['importe_bruto'];
            $subtotalDescuentos += $calculated['descuento_calculado'];
            $totalBaseImponible += $calculated['base_imponible'];
            $totalIva           += $calculated['iva_cuota'];
        }

        $this->subtotalBruto      = round($subtotalBruto, 2);
        $this->subtotalDescuentos = round($subtotalDescuentos, 2);
        $this->totalBaseImponible = round($totalBaseImponible, 2);
        $this->totalIva           = round($totalIva, 2);
        $this->total              = round($totalBaseImponible + $totalIva, 2);
    }

    public function openPlantillaModal(): void
    {
        $this->plantillaNombre = '';
        $this->plantillaSuccess = '';
        $this->showPlantillaModal = true;
    }

    public function closePlantillaModal(): void
    {
        $this->showPlantillaModal = false;
        $this->plantillaNombre = '';
    }

    public function openSelectPlantillaModal(): void
    {
        $this->selectPlantillaQuery = '';
        $this->selectPlantillaResults = [];
        $this->showSelectPlantillaModal = true;
        $this->updatedSelectPlantillaQuery();
    }

    public function closeSelectPlantillaModal(): void
    {
        $this->showSelectPlantillaModal = false;
        $this->selectPlantillaQuery = '';
        $this->selectPlantillaResults = [];
    }

    public function updatedSelectPlantillaQuery(): void
    {
        $empresaId = auth()->user()->company_id;
        $query = PlantillaPresupuesto::with('lineas')
            ->where('empresa_id', $empresaId)
            ->orderBy('nombre');

        if ($this->selectPlantillaQuery !== '') {
            $query->where('nombre', 'like', '%' . $this->selectPlantillaQuery . '%');
        }

        $this->selectPlantillaResults = $query->limit(50)->get()
            ->map(fn ($p) => [
                'id'        => $p->id,
                'nombre'    => $p->nombre,
                'lineas'    => $p->lineas->count(),
                'forma_pago' => $p->forma_pago,
            ])
            ->toArray();
    }

    public function applyPlantillaFromModal(int $plantillaId): void
    {
        $empresaId = auth()->user()->company_id;

        $plantilla = PlantillaPresupuesto::with('lineas')
            ->where('empresa_id', $empresaId)
            ->find($plantillaId);

        if (! $plantilla) {
            $this->closeSelectPlantillaModal();
            return;
        }

        $this->plantillaIdAplicada = $plantilla->id;

        if ($plantilla->negocio_id) {
            $this->negocioId = $plantilla->negocio_id;
        }
        $this->formaPago     = $plantilla->forma_pago ?? '';
        $this->observaciones = $plantilla->observaciones ?? '';

        // Replace lines with plantilla lines
        $this->lineas = [];
        $this->lineaSearch = [];
        $this->lineaDropdownVisible = [];
        $this->lineaSearchResults = [];

        foreach ($plantilla->lineas as $idx => $linea) {
            $this->lineas[] = [
                'concepto'        => $linea->concepto,
                'cantidad'        => (float) $linea->cantidad,
                'precio_unitario' => (float) $linea->precio_unitario,
                'descuento_tipo'  => $linea->descuento_tipo ?? '',
                'descuento_valor' => (float) ($linea->descuento_valor ?? 0),
                'iva_tipo'        => (float) $linea->iva_tipo,
                'servicio_id'     => null,
                'base_imponible'  => 0,
                'iva_cuota'       => 0,
                'total'           => 0,
            ];
            $this->lineaSearch[$idx] = $linea->concepto;
            $this->lineaDropdownVisible[$idx] = false;
            $this->lineaSearchResults[$idx] = [];
        }

        if (empty($this->lineas)) {
            $this->addLinea();
        }

        $this->recalcularTotales();
        $this->closeSelectPlantillaModal();

        // Advance past step 1 if empresa is selected
        if ($this->step === 1 && $this->negocioId) {
            $this->step = 2;
        }
    }

    public function saveAsPlantilla(): void
    {
        $this->validate([
            'plantillaNombre' => 'required|string|max:255',
        ], [
            'plantillaNombre.required' => 'El nombre de la plantilla es obligatorio.',
        ]);

        $empresaId = auth()->user()->company_id;

        $plantilla = PlantillaPresupuesto::create([
            'empresa_id'   => $empresaId,
            'negocio_id'   => $this->negocioId,
            'nombre'       => $this->plantillaNombre,
            'forma_pago'   => $this->formaPago ?: null,
            'observaciones' => $this->observaciones ?: null,
            'created_by'   => auth()->id(),
        ]);

        foreach ($this->lineas as $orden => $linea) {
            if (empty($linea['concepto'])) {
                continue;
            }
            PlantillaPresupuestoLinea::create([
                'plantilla_id'    => $plantilla->id,
                'orden'           => $orden,
                'concepto'        => $linea['concepto'],
                'cantidad'        => $linea['cantidad'],
                'precio_unitario' => $linea['precio_unitario'],
                'descuento_tipo'  => $linea['descuento_tipo'] ?: null,
                'descuento_valor' => $linea['descuento_tipo'] ? $linea['descuento_valor'] : null,
                'iva_tipo'        => $linea['iva_tipo'],
            ]);
        }

        $this->showPlantillaModal = false;
        $this->plantillaNombre = '';
        $this->plantillaSuccess = 'Plantilla guardada correctamente.';
    }

    public function render()
    {
        return view('presupuestos::livewire.presupuesto-form');
    }
}
