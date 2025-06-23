<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 4px 8px; }
        th { background: #e0f7fa; }
        h1, h2 { color: #00796b; }
    </style>
</head>
<body>
    <h1>Reporte {{ ucfirst($tipo) }} - {{ now()->format('Y-m-d') }}</h1>
    <h2>Pagos de Créditos</h2>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Valor</th>
                <th>Fecha pago</th>
                <th>Detalle</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pagosCreditos as $c)
            <tr>
                <td>{{ $c->usuario->nombres ?? '' }} {{ $c->usuario->apellidos ?? '' }}</td>
                <td>${{ number_format($c->valor, 0) }}</td>
                <td>{{ $c->updated_at->format('Y-m-d') }}</td>
                <td>{{ $c->detalle }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h2>Facturas Pagadas</h2>
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Ciclo</th>
                <th>Año</th>
                <th>Valor</th>
                <th>Fecha pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facturasPagadas as $f)
            <tr>
                <td>{{ $f->usuario->nombres ?? '' }} {{ $f->usuario->apellidos ?? '' }}</td>
                <td>{{ $f->ciclo }}</td>
                <td>{{ $f->anio }}</td>
                <td>${{ number_format($f->consumo_m3 <= 50 ? 22000 : 22000 + ($f->consumo_m3-50)*2500 + 5000, 0) }}</td>
                <td>{{ $f->fecha_pago }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @if($gastos->count())
    <h2>Gastos</h2>
    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Valor</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gastos as $g)
            <tr>
                <td>{{ $g->concepto }}</td>
                <td>${{ number_format($g->valor, 0) }}</td>
                <td>{{ $g->fecha }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html>
