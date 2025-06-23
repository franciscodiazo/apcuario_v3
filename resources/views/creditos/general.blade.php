@extends('layouts.app')
@section('content')
<div class="max-w-5xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Resumen General de Créditos</h2>
    <div class="flex flex-wrap gap-6 mb-6">
        <div class="bg-green-100 rounded px-6 py-4 text-green-900 font-bold text-lg">Total recaudado: ${{ number_format($totalRecaudado, 0) }}</div>
        <div class="bg-yellow-100 rounded px-6 py-4 text-yellow-900 font-bold text-lg">Total pendiente: ${{ number_format($totalPendiente, 0) }}</div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full text-xs md:text-sm border">
            <thead class="bg-cyan-100">
                <tr>
                    <th class="p-2">Usuario</th>
                    <th class="p-2">Matrícula</th>
                    <th class="p-2">Valor</th>
                    <th class="p-2">Saldo</th>
                    <th class="p-2">Estado</th>
                    <th class="p-2">Fecha</th>
                    <th class="p-2">Acuerdo</th>
                    <th class="p-2">Detalle</th>
                    <th class="p-2">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($creditos as $credito)
                <tr class="border-b">
                    <td class="p-2">{{ $credito->usuario->nombres ?? '' }} {{ $credito->usuario->apellidos ?? '' }}</td>
                    <td class="p-2">{{ $credito->matricula }}</td>
                    <td class="p-2">${{ number_format($credito->valor, 0) }}</td>
                    <td class="p-2">${{ number_format($credito->saldo, 0) }}</td>
                    <td class="p-2">{{ ucfirst($credito->estado) }}</td>
                    <td class="p-2">{{ $credito->fecha ?? ($credito->created_at ? $credito->created_at->format('Y-m-d') : '') }}</td>
                    <td class="p-2">{{ $credito->acuerdo ?? '' }}</td>
                    <td class="p-2">{{ $credito->detalle ?? '' }}</td>
                    <td class="p-2">
                        <a href="{{ route('creditos.index', ['matricula' => $credito->matricula]) }}" class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-800 transition">Ver usuario</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
