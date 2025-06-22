@extends('layouts.app')

@section('content')
<div class="w-full">
    <h1 class="text-2xl font-display font-bold text-aquarius-800 mb-6">Consumos y Costo a Pagar</h1>
    <form method="GET" class="flex flex-wrap gap-4 mb-6 items-end">
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Año</label>
            <input type="number" name="anio" value="{{ $anio }}" class="w-24 rounded border-aquarius-200 text-center focus:ring-coral-400" min="2020" max="2100" />
        </div>
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Ciclo</label>
            <input type="number" name="ciclo" value="{{ $ciclo }}" class="w-24 rounded border-aquarius-200 text-center focus:ring-coral-400" min="1" max="6" />
        </div>
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Estado</label>
            <select name="estado" class="rounded border-aquarius-200 px-2 py-1 focus:ring-coral-400">
                <option value="">Todos</option>
                <option value="pendiente" @selected(request('estado')=='pendiente')>Pendiente</option>
                <option value="pagado" @selected(request('estado')=='pagado')>Pagado</option>
            </select>
        </div>
        <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Filtrar</button>
    </form>
    <div class="mb-4">
        <div class="bg-sand-100 rounded-lg p-4 flex flex-wrap gap-6 items-center">
            <div><span class="font-bold">Año:</span> {{ $precios ? $precios->anio : $anio }}</div>
            <div><span class="font-bold">Costo base:</span> ${{ number_format($precios->costo_base ?? 22000, 0) }} hasta {{ $precios->limite_base ?? 50 }} m³</div>
            <div><span class="font-bold">Adicional:</span> ${{ number_format($precios->costo_adicional ?? 2500, 0) }} por m³ extra</div>
        </div>
    </div>
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="min-w-full divide-y divide-aquarius-200 bg-white">
            <thead class="bg-aquarius-100">
                <tr>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Ciclo</th>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Año</th>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Consumo (m³)</th>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Costo a pagar</th>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-3 text-xs font-bold text-aquarius-700 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-aquarius-100">
                @forelse($lecturas as $lectura)
                @php
                    $base = $precios->costo_base ?? 22000;
                    $limite = $precios->limite_base ?? 50;
                    $adicional = $precios->costo_adicional ?? 2500;
                    $consumo = $lectura->consumo_m3;
                    $costo = $consumo <= $limite ? $base : $base + ($consumo - $limite) * $adicional;
                @endphp
                <tr class="hover:bg-aquarius-50 transition">
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->usuario ? $lectura->usuario->matricula . ' - ' . $lectura->usuario->apellidos . ' ' . $lectura->usuario->nombres : $lectura->matricula }}</td>
                    <td class="px-4 py-2 text-center">{{ $lectura->ciclo }}</td>
                    <td class="px-4 py-2 text-center">{{ $lectura->anio }}</td>
                    <td class="px-4 py-2 text-center">{{ $consumo }}</td>
                    <td class="px-4 py-2 text-center font-bold text-coral-700">${{ number_format($costo, 0) }}</td>
                    <td class="px-4 py-2 text-center">
                        @if($lectura->pagado)
                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-bold">Pagado</span><br>
                            <span class="text-xs text-aquarius-500">{{ $lectura->metodo_pago }}</span>
                        @else
                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-700 text-xs font-bold">Pendiente</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">
                        <a href="{{ route('consumos.recibo', $lectura->id) }}" class="px-3 py-1 rounded bg-aquarius-200 text-aquarius-900 font-bold hover:bg-coral-200 transition">Recibo</a>
                        @if(!$lectura->pagado)
                        <form action="{{ route('consumos.pagar', $lectura->id) }}" method="POST" class="inline">
                            @csrf
                            <select name="metodo_pago" class="rounded border-aquarius-200 px-2 py-1 text-xs">
                                <option value="Banco">Banco</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <button type="submit" class="ml-2 px-3 py-1 rounded bg-coral-500 text-white font-bold hover:bg-coral-700 transition">Pagar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-aquarius-400">No hay lecturas registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $lecturas->links('pagination::tailwind') }}
    </div>
</div>
@endsection
