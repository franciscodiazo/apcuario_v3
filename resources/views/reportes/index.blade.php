@extends('layouts.app')
@section('content')
<div class="w-full max-w-7xl mx-auto">
    <h1 class="text-3xl font-display font-bold text-aquarius-900 mb-8 tracking-tight">Reportes</h1>
    <form method="GET" class="flex flex-wrap gap-4 mb-8 items-end">
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Tipo de reporte</label>
            <select name="tipo" class="rounded border-aquarius-200">
                <option value="diario" @if($tipo=='diario') selected @endif>Diario</option>
                <option value="mensual" @if($tipo=='mensual') selected @endif>Mensual</option>
                <option value="anual" @if($tipo=='anual') selected @endif>Anual</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Fecha</label>
            <input type="date" name="fecha" value="{{ $fecha }}" class="rounded border-aquarius-200" />
        </div>
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Año</label>
            <input type="number" name="anio" value="{{ $anio }}" class="w-24 rounded border-aquarius-200 text-center" min="2020" max="2100" />
        </div>
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Mes</label>
            <input type="number" name="mes" value="{{ $mes }}" class="w-16 rounded border-aquarius-200 text-center" min="1" max="12" />
        </div>
        <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Filtrar</button>
        <a href="{{ route('reportes.exportarPdf', request()->all()) }}" class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-800 transition">Descargar PDF</a>
    </form>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Pagos de Créditos</h2>
            <table class="min-w-full text-xs md:text-sm">
                <thead class="bg-cyan-100">
                    <tr>
                        <th class="px-2 py-2">Usuario</th>
                        <th class="px-2 py-2">Valor</th>
                        <th class="px-2 py-2">Fecha pago</th>
                        <th class="px-2 py-2">Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagosCreditos as $c)
                    <tr>
                        <td class="px-2 py-1">{{ $c->usuario->nombres ?? '' }} {{ $c->usuario->apellidos ?? '' }}</td>
                        <td class="px-2 py-1">${{ number_format($c->valor, 0) }}</td>
                        <td class="px-2 py-1">{{ $c->updated_at->format('Y-m-d') }}</td>
                        <td class="px-2 py-1">{{ $c->detalle }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-lg font-bold mb-4">Facturas Pagadas</h2>
            <table class="min-w-full text-xs md:text-sm">
                <thead class="bg-green-100">
                    <tr>
                        <th class="px-2 py-2">Usuario</th>
                        <th class="px-2 py-2">Ciclo</th>
                        <th class="px-2 py-2">Año</th>
                        <th class="px-2 py-2">Valor</th>
                        <th class="px-2 py-2">Fecha pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturasPagadas as $f)
                    <tr>
                        <td class="px-2 py-1">{{ $f->usuario->nombres ?? '' }} {{ $f->usuario->apellidos ?? '' }}</td>
                        <td class="px-2 py-1">{{ $f->ciclo }}</td>
                        <td class="px-2 py-1">{{ $f->anio }}</td>
                        <td class="px-2 py-1">${{ number_format($f->consumo_m3 <= 50 ? 22000 : 22000 + ($f->consumo_m3-50)*2500 + 5000, 0) }}</td>
                        <td class="px-2 py-1">{{ $f->fecha_pago }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($gastos->count())
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Gastos</h2>
        <table class="min-w-full text-xs md:text-sm">
            <thead class="bg-red-100">
                <tr>
                    <th class="px-2 py-2">Concepto</th>
                    <th class="px-2 py-2">Valor</th>
                    <th class="px-2 py-2">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gastos as $g)
                <tr>
                    <td class="px-2 py-1">{{ $g->concepto }}</td>
                    <td class="px-2 py-1">${{ number_format($g->valor, 0) }}</td>
                    <td class="px-2 py-1">{{ $g->fecha }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Gráficos de comportamiento</h2>
        <canvas id="graficoPagos" height="120"></canvas>
    </div>
    @if($tipo === 'anual' && $totalesPorMes->count())
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Totales por mes ({{ $anio }})</h2>
        <table class="min-w-full text-xs md:text-sm mb-4">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-2 py-2">Mes</th>
                    <th class="px-2 py-2">Total recaudado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totalesPorMes as $mes)
                <tr>
                    <td class="px-2 py-1">{{ DateTime::createFromFormat('!m', $mes->mes)->format('F') }}</td>
                    <td class="px-2 py-1">${{ number_format($mes->total, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <canvas id="graficoTotalesMes" height="80"></canvas>
    </div>
    <script>
    const totalesPorMes = @json($totalesPorMes);
    new Chart(document.getElementById('graficoTotalesMes'), {
        type: 'line',
        data: {
            labels: totalesPorMes.map(m => new Date(2000, m.mes-1, 1).toLocaleString('es-ES', { month: 'long' })),
            datasets: [{
                label: 'Total recaudado',
                data: totalesPorMes.map(m => m.total),
                backgroundColor: '#00bcd4',
                borderColor: '#00796b',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Totales por mes' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    </script>
    @endif
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ejemplo: pagos de créditos por día
    const pagosCreditos = @json($pagosCreditos);
    const facturasPagadas = @json($facturasPagadas);
    const labels = pagosCreditos.map(c => c.updated_at.substring(0,10));
    const data = pagosCreditos.map(c => c.valor);
    new Chart(document.getElementById('graficoPagos'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pagos de créditos',
                data: data,
                backgroundColor: '#00bcd4',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Pagos de créditos por fecha' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
