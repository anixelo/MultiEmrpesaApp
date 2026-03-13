<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto {{ $presupuesto->numero }}</title>
    <style>
        @page {
            margin: 28px 24px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        .document {
            width: 100%;
        }

        .header {
            width: 100%;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 18px;
            margin-bottom: 24px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }

        .header-left {
            width: 58%;
        }

        .header-right {
            width: 42%;
            text-align: right;
        }

        .brand-name {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 11px;
            color: #64748b;
        }

        .doc-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 4px;
        }

        .doc-number {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .meta-line {
            font-size: 11px;
            color: #475569;
            line-height: 1.5;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: bold;
            margin-top: 8px;
        }

        .badge-gray { background: #f1f5f9; color: #475569; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-purple { background: #ede9fe; color: #7c3aed; }
        .badge-green { background: #d1fae5; color: #065f46; }
        .badge-red { background: #ffe4e6; color: #be123c; }

        .info-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0;
            margin-bottom: 22px;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            padding: 0;
            border: none;
        }

        .info-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 14px 16px;
            min-height: 110px;
        }

        .info-box-left {
            margin-right: 8px;
        }

        .info-box-right {
            margin-left: 8px;
        }

        .section-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .info-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 6px;
        }

        .info-text {
            font-size: 11px;
            color: #334155;
            line-height: 1.55;
        }

        .info-text div {
            margin-bottom: 2px;
        }

        .lineas-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
        }

        .lineas-table thead th {
            background: #eef2ff;
            color: #4338ca;
            text-align: left;
            padding: 10px 8px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-top: 1px solid #c7d2fe;
            border-bottom: 1px solid #c7d2fe;
        }

        .lineas-table tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            vertical-align: top;
        }

        .lineas-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .concepto {
            font-weight: 600;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .concepto-extra {
            font-size: 10px;
            color: #64748b;
        }

        .totals-wrapper {
            width: 100%;
            margin-top: 8px;
            margin-bottom: 24px;
        }

        .totals-table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }

        .totals-table td {
            padding: 9px 12px;
            font-size: 11px;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-table tr:last-child td {
            border-bottom: none;
        }

        .totals-table .label-cell {
            background: #f8fafc;
            color: #334155;
        }

        .totals-table .value-cell {
            text-align: right;
            font-weight: 600;
            color: #0f172a;
            background: #ffffff;
        }

        .totals-table .discount-row .label-cell,
        .totals-table .discount-row .value-cell {
            color: #dc2626;
        }

        .totals-table .total-row .label-cell,
        .totals-table .total-row .value-cell {
            background: #4f46e5;
            color: #ffffff;
            font-size: 13px;
            font-weight: bold;
        }

        .extra-section {
            margin-bottom: 18px;
        }

        .extra-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 11px;
            color: #334155;
            line-height: 1.6;
        }

        .footer-note {
            margin-top: 28px;
            padding-top: 14px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>
<body>
@php
    $badgeClass = match($presupuesto->estado) {
        'enviado' => 'badge-blue',
        'visto' => 'badge-purple',
        'aceptado' => 'badge-green',
        'rechazado' => 'badge-red',
        default => 'badge-gray'
    };

    $estadoLabels = [
        'borrador' => 'Borrador',
        'enviado' => 'Enviado',
        'visto' => 'Visto',
        'aceptado' => 'Aceptado',
        'rechazado' => 'Rechazado',
    ];

    $empresaNombre = $presupuesto->negocio?->name ?? $presupuesto->empresa?->name ?? config('app.name');
@endphp

<div class="document">

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    @if($presupuesto->negocio?->logo)
                        @php
                            $logoRelPath = $presupuesto->negocio->logo;
                            $logoPath = realpath(public_path('storage/' . $logoRelPath));
                            $storageBase = realpath(public_path('storage'));
                            $logoSafe = $logoPath && str_starts_with($logoPath, $storageBase);
                        @endphp
                        @if($logoSafe && file_exists($logoPath))
                            <img src="{{ 'file://' . $logoPath }}" alt="{{ $empresaNombre }}" style="max-height:60px;max-width:180px;margin-bottom:6px;object-fit:contain;">
                        @else
                            <div class="brand-name">{{ $empresaNombre }}</div>
                        @endif
                    @else
                        <div class="brand-name">{{ $empresaNombre }}</div>
                    @endif
                    <div class="brand-subtitle">Documento de presupuesto</div>
                </td>
                <td class="header-right">
                    <div class="doc-title">Presupuesto</div>
                    <div class="doc-number">{{ $presupuesto->numero }}</div>
                    <div class="meta-line">Fecha: {{ $presupuesto->fecha->format('d/m/Y') }}</div>
                    @if($presupuesto->validez_hasta)
                        <div class="meta-line">Válido hasta: {{ $presupuesto->validez_hasta->format('d/m/Y') }}</div>
                    @endif
                    <div>
                        <span class="badge {{ $badgeClass }}">
                            {{ $estadoLabels[$presupuesto->estado] ?? $presupuesto->estado }}
                        </span>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-table">
        <tr>
            <td>
                <div class="info-box info-box-left">
                    <div class="section-label">Emisor</div>
                    <div class="info-name">{{ $empresaNombre }}</div>
                    <div class="info-text">
                        @if($presupuesto->empresa?->email)
                            <div>{{ $presupuesto->empresa->email }}</div>
                        @endif
                        @if($presupuesto->empresa?->telefono)
                            <div>{{ $presupuesto->empresa->telefono }}</div>
                        @endif
                        @if($presupuesto->empresa?->direccion)
                            <div>{{ $presupuesto->empresa->direccion }}</div>
                        @endif
                    </div>
                </div>
            </td>
            <td>
                <div class="info-box info-box-right">
                    <div class="section-label">Destinatario</div>
                    <div class="info-name">{{ $presupuesto->cliente?->nombre ?? '—' }}</div>
                    <div class="info-text">
                        @if($presupuesto->cliente?->email)
                            <div>{{ $presupuesto->cliente->email }}</div>
                        @endif
                        @if($presupuesto->cliente?->telefono)
                            <div>{{ $presupuesto->cliente->telefono }}</div>
                        @endif
                        @if($presupuesto->cliente?->direccion)
                            <div>{{ $presupuesto->cliente->direccion }}</div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="lineas-table">
        <thead>
            <tr>
                <th style="width: 34%;">Concepto</th>
                <th class="text-right nowrap" style="width: 9%;">Cant.</th>
                <th class="text-right nowrap" style="width: 14%;">P. unit.</th>
                <th class="text-right nowrap" style="width: 11%;">Dto.</th>
                <th class="text-right nowrap" style="width: 12%;">Base imp.</th>
                <th class="text-right nowrap" style="width: 8%;">IVA</th>
                <th class="text-right nowrap" style="width: 12%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presupuesto->lineas as $linea)
                <tr>
                    <td>
                        <div class="concepto">{{ $linea->concepto }}</div>
                        @if(!empty($linea->descripcion))
                            <div class="concepto-extra">{{ $linea->descripcion }}</div>
                        @endif
                    </td>
                    <td class="text-right nowrap">{{ number_format($linea->cantidad, 2, ',', '.') }}</td>
                    <td class="text-right nowrap">{{ number_format($linea->precio_unitario, 2, ',', '.') }} €</td>
                    <td class="text-right nowrap">
                        @if($linea->descuento_tipo === 'porcentaje' && $linea->descuento_valor)
                            {{ number_format($linea->descuento_valor, 2, ',', '.') }}%
                        @elseif($linea->descuento_tipo === 'importe' && $linea->descuento_valor)
                            {{ number_format($linea->descuento_valor, 2, ',', '.') }} €
                        @else
                            —
                        @endif
                    </td>
                    <td class="text-right nowrap">{{ number_format($linea->base_imponible, 2, ',', '.') }} €</td>
                    <td class="text-right nowrap">{{ number_format($linea->iva_tipo, 0) }}%</td>
                    <td class="text-right nowrap"><strong>{{ number_format($linea->total, 2, ',', '.') }} €</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-wrapper">
        <table class="totals-table">
            <tbody>
                <tr>
                    <td class="label-cell">Subtotal bruto</td>
                    <td class="value-cell">{{ number_format($presupuesto->subtotal_bruto, 2, ',', '.') }} €</td>
                </tr>

                @if($presupuesto->subtotal_descuentos > 0)
                    <tr class="discount-row">
                        <td class="label-cell">Descuentos</td>
                        <td class="value-cell">- {{ number_format($presupuesto->subtotal_descuentos, 2, ',', '.') }} €</td>
                    </tr>
                @endif

                <tr>
                    <td class="label-cell">Base imponible</td>
                    <td class="value-cell">{{ number_format($presupuesto->total_base_imponible, 2, ',', '.') }} €</td>
                </tr>
                <tr>
                    <td class="label-cell">IVA</td>
                    <td class="value-cell">{{ number_format($presupuesto->total_iva, 2, ',', '.') }} €</td>
                </tr>
                <tr class="total-row">
                    <td class="label-cell">Total</td>
                    <td class="value-cell">{{ number_format($presupuesto->total, 2, ',', '.') }} €</td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($presupuesto->observaciones)
        <div class="extra-section">
            <div class="section-label">Observaciones</div>
            <div class="extra-box">
                {{ $presupuesto->observaciones }}
            </div>
        </div>
    @endif

    @if($presupuesto->forma_pago)
        <div class="extra-section">
            <div class="section-label">Forma de pago</div>
            <div class="extra-box">
                {{ $presupuesto->forma_pago }}
            </div>
        </div>
    @endif

    <div class="footer-note">
        Gracias por confiar en {{ $empresaNombre }}.
        @if($presupuesto->validez_hasta)
            Este presupuesto es válido hasta el {{ $presupuesto->validez_hasta->format('d/m/Y') }}.
        @endif
    </div>

</div>
</body>
</html>