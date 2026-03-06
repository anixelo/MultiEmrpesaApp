<?php

namespace MultiempresaApp\Presupuestos\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
use MultiempresaApp\Presupuestos\Models\PresupuestoConfiguracion;
use MultiempresaApp\Presupuestos\Services\PresupuestoCalculator;

class PresupuestoForm extends Component
{
    public ?int $presupuestoId = null;
    public ?int $clienteId = null;
    public string $clienteNombre = '';
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

    public function mount(?int $presupuestoId = null): void
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

        if ($presupuestoId) {
            $this->presupuestoId = $presupuestoId;
            $presupuesto = Presupuesto::with('lineas')->findOrFail($presupuestoId);

            $this->clienteId    = $presupuesto->cliente_id;
            $this->clienteNombre = $presupuesto->cliente?->nombre ?? '';
            $this->fecha        = $presupuesto->fecha->format('Y-m-d');
            $this->validezHasta = $presupuesto->validez_hasta?->format('Y-m-d') ?? '';
            $this->formaPago    = $presupuesto->forma_pago ?? '';
            $this->observaciones = $presupuesto->observaciones ?? '';
            $this->notas        = $presupuesto->notas ?? '';

            foreach ($presupuesto->lineas as $linea) {
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
            }

            $this->recalcularTotales();
        }

        if (empty($this->lineas)) {
            $this->addLinea();
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
    }

    public function removeLinea(int $index): void
    {
        array_splice($this->lineas, $index, 1);
        $this->recalcularTotales();
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

    public function render()
    {
        return view('presupuestos::livewire.presupuesto-form');
    }
}
