@extends('layouts.app')
@section('content')
<div class="w-full max-w-3xl mx-auto">
    <h1 class="text-3xl font-display font-bold text-aquarius-900 mb-8 tracking-tight">Reporte Anual de Recaudo</h1>
    <form method="GET" class="flex gap-4 mb-8 items-end">
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Año</label>
            <input type="number" name="anio" value="{{ $anio }}" class="w-24 rounded border-aquarius-200 text-center" min="2020" max="2100" />
        </div>
        <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Ver</button>
    </form>
    <div class="flex justify-end mb-4">
        <a href="{{ route('reportes.anualPdf', ['anio' => $anio]) }}" class="px-6 py-2 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-800 transition">Descargar PDF</a>
    </div>
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
        <canvas id="graficoTotalesMes" height="100"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
@endsection
