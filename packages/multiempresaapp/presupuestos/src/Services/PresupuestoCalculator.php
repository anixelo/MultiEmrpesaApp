<?php

namespace MultiempresaApp\Presupuestos\Services;

use MultiempresaApp\Presupuestos\Models\Presupuesto;
use MultiempresaApp\Presupuestos\Models\PresupuestoConfiguracion;

class PresupuestoCalculator
{
    public function calcularLinea(array $data, float $ivaDefecto): array
    {
        $cantidad       = (float) ($data['cantidad'] ?? 1);
        $precioUnitario = (float) ($data['precio_unitario'] ?? 0);
        $descuentoTipo  = $data['descuento_tipo'] ?? null;
        $descuentoValor = (float) ($data['descuento_valor'] ?? 0);
        $ivaTipo        = isset($data['iva_tipo']) && $data['iva_tipo'] !== '' && $data['iva_tipo'] !== null
            ? (float) $data['iva_tipo']
            : $ivaDefecto;

        $importeBruto = $cantidad * $precioUnitario;
        $descuento    = 0;

        if ($descuentoTipo === 'porcentaje' && $descuentoValor > 0) {
            $descuento = $importeBruto * $descuentoValor / 100;
        } elseif ($descuentoTipo === 'importe' && $descuentoValor > 0) {
            $descuento = $descuentoValor;
        }

        $baseImponible = $importeBruto - $descuento;
        $ivaCuota      = $baseImponible * $ivaTipo / 100;
        $total         = $baseImponible + $ivaCuota;

        return array_merge($data, [
            'importe_bruto'      => round($importeBruto, 2),
            'descuento_calculado' => round($descuento, 2),
            'base_imponible'     => round($baseImponible, 2),
            'iva_tipo'           => $ivaTipo,
            'iva_cuota'          => round($ivaCuota, 2),
            'total'              => round($total, 2),
        ]);
    }

    public function calcularTotales(Presupuesto $presupuesto): void
    {
        $lineas = $presupuesto->lineas;

        $subtotalBruto      = 0;
        $subtotalDescuentos = 0;
        $totalBaseImponible = 0;
        $totalIva           = 0;

        foreach ($lineas as $linea) {
            $cantidad     = (float) $linea->cantidad;
            $precio       = (float) $linea->precio_unitario;
            $importeBruto = $cantidad * $precio;

            $subtotalBruto      += $importeBruto;
            $subtotalDescuentos += ($importeBruto - (float) $linea->base_imponible);
            $totalBaseImponible += (float) $linea->base_imponible;
            $totalIva           += (float) $linea->iva_cuota;
        }

        $presupuesto->subtotal_bruto      = round($subtotalBruto, 2);
        $presupuesto->subtotal_descuentos = round($subtotalDescuentos, 2);
        $presupuesto->total_base_imponible = round($totalBaseImponible, 2);
        $presupuesto->total_iva           = round($totalIva, 2);
        $presupuesto->total               = round($totalBaseImponible + $totalIva, 2);
        $presupuesto->save();
    }

    public function generarNumero(PresupuestoConfiguracion $config): string
    {
        $prefijo = $config->prefijo ?? 'PRE';
        $numero  = str_pad($config->siguiente_numero, 4, '0', STR_PAD_LEFT);
        $config->siguiente_numero += 1;
        $config->save();

        return $prefijo . '-' . $numero;
    }
}
