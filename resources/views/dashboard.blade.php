@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <h1 class="text-3xl font-display font-bold text-aquarius-900 mb-8 tracking-tight">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-coral-100 to-coral-300 rounded-2xl shadow-lg p-6 flex flex-col items-center">
            <div class="text-5xl font-bold text-coral-700 mb-2">{{ $usuariosCount }}</div>
            <div class="text-lg font-semibold text-coral-900">Usuarios registrados</div>
        </div>
        <div class="bg-gradient-to-br from-green-100 to-green-300 rounded-2xl shadow-lg p-6 flex flex-col items-center">
            <div class="text-3xl font-bold text-green-700 mb-2">${{ number_format($pagosTotales, 0) }}</div>
            <div class="text-lg font-semibold text-green-900">Total Recaudado</div>
        </div>
        <div class="bg-gradient-to-br from-blue-100 to-blue-300 rounded-2xl shadow-lg p-6 flex flex-col items-center">
            <div class="text-2xl font-bold text-blue-700 mb-2">{{ $pagosPorMetodo->sum('cantidad') }}</div>
            <div class="text-lg font-semibold text-blue-900">Pagos realizados</div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Pagos por método</div>
            <table class="min-w-full divide-y divide-aquarius-200">
                <thead class="bg-aquarius-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Método</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Cantidad</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-aquarius-100">
                    @foreach($pagosPorMetodo as $pago)
                    <tr>
                        <td class="px-4 py-2">{{ $pago->metodo_pago ?? 'Sin especificar' }}</td>
                        <td class="px-4 py-2">{{ $pago->cantidad }}</td>
                        <td class="px-4 py-2">${{ number_format($pago->total, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Pagos por mes y año</div>
            <canvas id="pagosMesChart" height="120"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Lecturas registradas por año y ciclo</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-aquarius-200">
                    <thead class="bg-aquarius-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Año</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Ciclo</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Total lecturas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-aquarius-100">
                        @forelse($lecturasPorAnioCiclo as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->anio }}</td>
                            <td class="px-4 py-2">{{ $item->ciclo }}</td>
                            <td class="px-4 py-2">{{ $item->total }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-aquarius-400">No hay lecturas registradas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Última lectura registrada</div>
            @if($ultimaLectura)
                <div class="flex flex-col gap-2">
                    <div class="text-base text-aquarius-700">Matrícula: <span class="font-bold">{{ $ultimaLectura->matricula }}</span></div>
                    <div class="text-base text-aquarius-700">Usuario: <span class="font-bold">{{ $ultimaLectura->usuario ? $ultimaLectura->usuario->nombres . ' ' . $ultimaLectura->usuario->apellidos : '' }}</span></div>
                    <div class="text-base text-aquarius-700">Lectura: <span class="font-bold">{{ $ultimaLectura->lectura_actual }}</span></div>
                    <div class="text-xs text-sand-700">Fecha: {{ $ultimaLectura->fecha }}</div>
                </div>
            @else
                <div class="text-aquarius-400">No hay lecturas registradas.</div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const pagosMesData = @json($pagosPorMes);
    const labels = pagosMesData.map(item => `${item.anio}-${String(item.mes).padStart(2,'0')}`);
    const data = pagosMesData.map(item => item.total);
    new Chart(document.getElementById('pagosMesChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Recaudo mensual',
                data: data,
                backgroundColor: '#00bcd4',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                title: { display: true, text: 'Recaudo por mes' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection