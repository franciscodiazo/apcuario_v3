@extends('layouts.app')

@section('content')
<div class="w-full max-w-lg mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-xl font-bold mb-4 text-aquarius-800">Recibo de Pago</h2>
        <div class="mb-4">
            <div class="flex justify-between mb-2">
                <span class="font-bold">Matrícula:</span>
                <span>{{ $lectura->matricula }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Nombres:</span>
                <span>{{ $lectura->usuario->nombres ?? '' }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Apellidos:</span>
                <span>{{ $lectura->usuario->apellidos ?? '' }}</span>
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
                $limite = $precios->limite_base ?? 50;
                $basico = $precios->costo_base ?? 22000;
                $adicional_m3 = $precios->costo_adicional ?? 2500;
                $consumo = $lectura->consumo_m3;
                $adicionales = max(0, $consumo - $limite);
                $valor_basico = $basico;
                $valor_adicional = $adicionales * $adicional_m3;
                $valor_factura = $valor_basico + $valor_adicional;
            @endphp
            <div class="flex flex-col gap-1 mb-2">
                <div class="flex justify-between">
                    <span class="font-bold">Consumo básico:</span>
                    <span>Hasta {{ $limite }} m³</span>
                    <span>${{ number_format($valor_basico, 0) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold">Consumo adicional:</span>
                    <span>{{ $adicionales }} m³ x ${{ number_format($adicional_m3, 0) }}</span>
                    <span>${{ number_format($valor_adicional, 0) }}</span>
                </div>
                <div class="flex justify-between font-bold bg-blue-50 rounded px-2 py-1 mt-1">
                    <span>Total a pagar:</span>
                    <span></span>
                    <span class="text-coral-700 text-lg">${{ number_format($valor_factura, 0) }}</span>
                </div>
            </div>
            @if($lectura->pagado)
            <div class="flex justify-between mb-2">
                <span class="font-bold">Método de pago:</span>
                <span>{{ $lectura->metodo_pago }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-bold">Fecha de pago:</span>
                <span>{{ $lectura->fecha_pago }}</span>
            </div>
            @endif
        </div>
        <a href="{{ route('consumos.index') }}" class="block mt-6 text-center px-6 py-2 rounded-lg bg-aquarius-700 text-white font-bold hover:bg-coral-600 transition">Volver</a>
    </div>
</div>
@endsection
