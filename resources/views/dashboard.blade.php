@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <h1 class="text-3xl font-display font-bold text-aquarius-900 mb-8 tracking-tight">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('usuarios.index') }}" class="bg-gradient-to-br from-coral-100 to-coral-300 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-5xl font-bold text-coral-700 mb-2">{{ $usuariosCount }}</div>
            <div class="text-lg font-semibold text-coral-900">Usuarios registrados</div>
        </a>
        <a href="{{ route('facturas.masiva') }}" class="bg-gradient-to-br from-green-100 to-green-300 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-3xl font-bold text-green-700 mb-2">${{ number_format($pagosTotales, 0) }}</div>
            <div class="text-lg font-semibold text-green-900">Total Recaudado</div>
        </a>
        <a href="{{ route('consumos.index') }}" class="bg-gradient-to-br from-blue-100 to-blue-300 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-2xl font-bold text-blue-700 mb-2">{{ $pagosPorMetodo->sum('cantidad') }}</div>
            <div class="text-lg font-semibold text-blue-900">Pagos realizados</div>
            <div class="text-base font-bold text-blue-800 mt-2">Pagadas: {{ $facturasPagadas }}</div>
            <div class="text-base font-bold text-yellow-700 mt-1">Pendientes: {{ $facturasPendientes }}</div>
        </a>
        <a href="{{ route('creditos.index') }}" class="bg-gradient-to-br from-cyan-100 to-cyan-300 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-2xl font-bold text-cyan-700 mb-2">{{ $creditosCount }}</div>
            <div class="text-lg font-semibold text-cyan-900">Créditos registrados</div>
            <div class="text-base font-bold text-cyan-800 mt-2">Total: ${{ number_format($creditosTotal, 0) }}</div>
            <div class="text-base font-bold text-green-700 mt-2">Pagados: ${{ number_format($creditosPagados, 0) }}</div>
            <div class="text-base font-bold text-yellow-700 mt-1">Pendientes: ${{ number_format($creditosPendientes, 0) }}</div>
        </a>
        <a href="{{ route('tarifas.index') }}" class="bg-gradient-to-br from-yellow-100 to-yellow-300 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-2xl font-bold text-yellow-700 mb-2">{{ $tarifaActual ? $tarifaActual->anio : 'Sin tarifa' }}</div>
            <div class="text-lg font-semibold text-yellow-900">Tarifa actual</div>
            @if($tarifaActual)
            <div class="text-base font-bold text-yellow-800 mt-2">Básico: ${{ number_format($tarifaActual->basico,0,',','.') }}</div>
            <div class="text-base font-bold text-yellow-800 mt-1">Adicional m³: ${{ number_format($tarifaActual->adicional_m3,0,',','.') }}</div>
            @else
            <div class="text-base font-bold text-yellow-800 mt-2">No registrada</div>
            @endif
        </a>
        <a href="{{ route('reportes.index') }}" class="bg-gradient-to-br from-blue-50 to-blue-200 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-2xl font-bold text-blue-900 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-8 w-8 inline' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-2l-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2z' /></svg></div>
            <div class="text-lg font-semibold text-blue-900">Reportes</div>
            <div class="text-base font-bold text-blue-800 mt-2">Ver y exportar</div>
        </a>
        <a href="{{ route('reportes.anual') }}" class="bg-gradient-to-br from-blue-200 to-blue-400 rounded-2xl shadow-lg p-6 flex flex-col items-center hover:scale-105 transition-transform">
            <div class="text-2xl font-bold text-blue-900 mb-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-8 w-8 inline' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 4h6a2 2 0 002-2V7a2 2 0 00-2-2h-2l-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2z' /></svg></div>
            <div class="text-lg font-semibold text-blue-900">Reporte Anual</div>
            <div class="text-base font-bold text-blue-800 mt-2">Totales por mes</div>
        </a>
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 overflow-x-auto">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Totales por ciclo/año</div>
            <table class="min-w-full divide-y divide-aquarius-200 text-xs md:text-sm">
                <thead class="bg-aquarius-100">
                    <tr>
                        <th class="px-2 py-2">Año</th>
                        <th class="px-2 py-2">Ciclo</th>
                        <th class="px-2 py-2">Total facturas</th>
                        <th class="px-2 py-2">Total recaudado</th>
                        <th class="px-2 py-2">Créditos</th>
                        <th class="px-2 py-2">Créditos pagados</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($facturasPorCiclo as $f)
                        <tr>
                            <td class="px-2 py-1">{{ $f->anio }}</td>
                            <td class="px-2 py-1">{{ $f->ciclo }}</td>
                            <td class="px-2 py-1">${{ number_format($f->total_facturas, 0) }}</td>
                            <td class="px-2 py-1">
                                @php
                                    $rec = $recaudadoPorCiclo->first(fn($r) => $r->anio == $f->anio && $r->ciclo == $f->ciclo);
                                @endphp
                                ${{ number_format($rec->total_recaudado ?? 0, 0) }}
                            </td>
                            <td class="px-2 py-1">
                                @php
                                    $cred = $creditosPorCiclo->first(fn($c) => $c->anio == $f->anio && $c->ciclo == $f->ciclo);
                                @endphp
                                ${{ number_format($cred->total_creditos ?? 0, 0) }}
                            </td>
                            <td class="px-2 py-1">
                                @php
                                    $credPag = $creditosPagadosPorCiclo->first(fn($c) => $c->anio == $f->anio && $c->ciclo == $f->ciclo);
                                @endphp
                                ${{ number_format($credPag->total_creditos_pagados ?? 0, 0) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
    </div>
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Pagos por mes y año</div>
            <canvas id="pagosMesChart" height="120"></canvas>
        </div>
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
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
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