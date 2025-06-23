@extends('layouts.app')
@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-xl font-bold mb-4 text-aquarius-800">Previsualización de Factura</h2>
        @auth
            @php $user = Auth::user(); @endphp
            @if($user && ($user->hasRole('admin') || $user->hasRole('operador')))
                <div class="max-w-2xl mx-auto my-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-900 rounded">
                    <strong>Advertencia:</strong> Estás visualizando la factura como administrador/operador. Ten cuidado con la información sensible.
                </div>
            @endif
        @endauth
        <div class="mb-4">
            <div class="flex justify-between mb-2">
                <span class="font-bold">Matrícula:</span>
                <span>{{ $lectura->matricula }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Nombres:</span>
                <span>{{ $usuario->nombres ?? '' }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Apellidos:</span>
                <span>{{ $usuario->apellidos ?? '' }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Año:</span>
                <span>{{ $lectura->anio }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Ciclo:</span>
                <span>{{ $lectura->ciclo }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Lectura anterior:</span>
                <span>{{ $lectura->lectura_anterior }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Lectura actual:</span>
                <span>{{ $lectura->lectura_actual }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Consumo (m³):</span>
                <span>{{ $lectura->consumo_m3 }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Estado:</span>
                <span>
                    @if($lectura->pagado)
                        <span class="text-green-700 font-bold">Pagado</span>
                    @else
                        <span class="text-yellow-700 font-bold">Pendiente</span>
                    @endif
                </span>
            </div>
            @php
                $base = $precios->basico ?? 22000;
                $adicional = $precios->adicional_m3 ?? 2500;
                $consumo = $lectura->consumo_m3;
                $costo = $base + ($consumo * $adicional);
            @endphp
            <div class="flex justify-between mb-2">
                <span class="font-bold">Valor a pagar:</span>
                <span class="text-coral-700 font-bold text-lg">${{ number_format($costo, 0) }}</span>
            </div>
        </div>
        <div class="flex gap-4 mt-6">
            <a href="{{ route('cliente.factura.pdf', $lectura->id) }}" class="px-6 py-2 rounded-lg bg-green-600 text-white font-bold shadow hover:bg-green-800 transition" target="_blank">Descargar PDF</a>
            <button onclick="window.print()" class="px-6 py-2 rounded-lg bg-coral-600 text-white font-bold shadow hover:bg-coral-800 transition">Imprimir</button>
            <a href="{{ url()->previous() }}" class="px-6 py-2 rounded-lg bg-gray-300 text-aquarius-900 font-bold shadow hover:bg-gray-400 transition">Volver</a>
        </div>
    </div>
</div>
@endsection
