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
    <h1>Reporte Anual de Recaudo - {{ $anio }}</h1>
    <h2>Totales por mes</h2>
    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th>Total recaudado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($totalesPorMes as $mes)
            <tr>
                <td>{{ DateTime::createFromFormat('!m', $mes->mes)->format('F') }}</td>
                <td>${{ number_format($mes->total, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
