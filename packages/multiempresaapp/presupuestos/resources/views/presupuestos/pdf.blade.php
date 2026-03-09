<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto {{ $presupuesto->numero }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 24px; border-bottom: 2px solid #4F46E5; padding-bottom: 16px; }
        .empresa-nombre { font-size: 20px; font-weight: bold; color: #4F46E5; }
        .numero { font-size: 16px; font-weight: bold; text-align: right; }
        .meta { font-size: 11px; color: #555; text-align: right; }
        .section { margin-bottom: 16px; }
        .label { font-size: 10px; text-transform: uppercase; color: #888; letter-spacing: 0.05em; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #F3F4F6; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; color: #555; border-bottom: 1px solid #E5E7EB; }
        td { padding: 6px 8px; border-bottom: 1px solid #F3F4F6; font-size: 11px; }
        .text-right { text-align: right; }
        .totals { margin-left: auto; width: 240px; }
        .totals td { border: none; padding: 3px 8px; }
        .total-row td { font-weight: bold; font-size: 13px; border-top: 2px solid #111; padding-top: 6px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 10px; font-weight: bold; }
        .badge-gray { background: #F3F4F6; color: #555; }
        .badge-blue { background: #DBEAFE; color: #1D4ED8; }
        .badge-purple { background: #EDE9FE; color: #7C3AED; }
        .badge-green { background: #D1FAE5; color: #065F46; }
        .badge-red { background: #FEE2E2; color: #991B1B; }
        .obs { background: #F9FAFB; padding: 10px 12px; border-left: 3px solid #E5E7EB; font-size: 11px; color: #374151; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <div class="empresa-nombre">{{ $presupuesto->negocio?->name ?? $presupuesto->empresa?->name ?? config('app.name') }}</div>
        <div style="margin-top:4px;font-size:11px;color:#555;">Presupuesto</div>
    </div>
    <div>
        <div class="numero">{{ $presupuesto->numero }}</div>
        <div class="meta">Fecha: {{ $presupuesto->fecha->format('d/m/Y') }}</div>
        @if($presupuesto->validez_hasta)
        <div class="meta">Válido hasta: {{ $presupuesto->validez_hasta->format('d/m/Y') }}</div>
        @endif
        @php
            $badgeClass = match($presupuesto->estado) {
                'enviado' => 'badge-blue', 'visto' => 'badge-purple',
                'aceptado' => 'badge-green', 'rechazado' => 'badge-red',
                default => 'badge-gray'
            };
            $estadoLabels = ['borrador'=>'Borrador','enviado'=>'Enviado','visto'=>'Visto','aceptado'=>'Aceptado','rechazado'=>'Rechazado'];
        @endphp
        <div style="margin-top:6px;"><span class="badge {{ $badgeClass }}">{{ $estadoLabels[$presupuesto->estado] ?? $presupuesto->estado }}</span></div>
    </div>
</div>

<div class="section">
    <div class="label">Destinatario</div>
    <div style="font-weight:bold;">{{ $presupuesto->cliente?->nombre ?? '—' }}</div>
    @if($presupuesto->cliente?->email) <div>{{ $presupuesto->cliente->email }}</div> @endif
    @if($presupuesto->cliente?->telefono) <div>{{ $presupuesto->cliente->telefono }}</div> @endif
</div>

<table>
    <thead>
        <tr>
            <th>Concepto</th>
            <th class="text-right">Cant.</th>
            <th class="text-right">P. Unit.</th>
            <th class="text-right">Dto.</th>
            <th class="text-right">Base Imp.</th>
            <th class="text-right">IVA</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($presupuesto->lineas as $linea)
        <tr>
            <td>{{ $linea->concepto }}</td>
            <td class="text-right">{{ number_format($linea->cantidad, 2, ',', '.') }}</td>
            <td class="text-right">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</td>
            <td class="text-right">
                @if($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor) {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                @elseif($linea->descuento_tipo === 'importe' && $linea->descuento_valor) {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                @else —
                @endif
            </td>
            <td class="text-right">{{ number_format($linea->base_imponible, 2, ',', '.') }} €</td>
            <td class="text-right">{{ number_format($linea->iva_tipo, 0) }}%</td>
            <td class="text-right"><strong>{{ number_format($linea->total, 2, ',', '.') }} €</strong></td>
        </tr>
        @endforeach
    </tbody>
</table>

<table class="totals">
    <tbody>
        <tr><td>Subtotal bruto</td><td class="text-right">{{ number_format($presupuesto->subtotal_bruto, 2, ',', '.') }} €</td></tr>
        @if($presupuesto->subtotal_descuentos > 0)
        <tr><td style="color:#DC2626;">Descuentos</td><td class="text-right" style="color:#DC2626;">- {{ number_format($presupuesto->subtotal_descuentos, 2, ',', '.') }} €</td></tr>
        @endif
        <tr><td>Base imponible</td><td class="text-right">{{ number_format($presupuesto->total_base_imponible, 2, ',', '.') }} €</td></tr>
        <tr><td>IVA</td><td class="text-right">{{ number_format($presupuesto->total_iva, 2, ',', '.') }} €</td></tr>
        <tr class="total-row"><td>TOTAL</td><td class="text-right">{{ number_format($presupuesto->total, 2, ',', '.') }} €</td></tr>
    </tbody>
</table>

@if($presupuesto->observaciones)
<div class="section">
    <div class="label">Observaciones</div>
    <div class="obs">{{ $presupuesto->observaciones }}</div>
</div>
@endif

@if($presupuesto->forma_pago)
<div class="section">
    <div class="label">Forma de pago</div>
    <div>{{ $presupuesto->forma_pago }}</div>
</div>
@endif

</body>
</html>
