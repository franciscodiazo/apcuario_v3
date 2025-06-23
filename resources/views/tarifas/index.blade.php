@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto mt-8 p-6 bg-white/90 rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-coral-700">Tarifas por Año</h2>
        <a href="{{ route('tarifas.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg font-bold hover:bg-green-800 transition">Nueva Tarifa</a>
    </div>
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded text-center font-semibold">{{ session('success') }}</div>
    @endif
    <table class="w-full text-left border border-coral-200 rounded-lg overflow-hidden">
        <thead class="bg-coral-100">
            <tr>
                <th class="px-4 py-2">Año</th>
                <th class="px-4 py-2">Básico (COP)</th>
                <th class="px-4 py-2">Adicional m³ (COP)</th>
                <th class="px-4 py-2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tarifas as $tarifa)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $tarifa->anio }}</td>
                <td class="px-4 py-2">${{ number_format($tarifa->basico,0,',','.') }}</td>
                <td class="px-4 py-2">${{ number_format($tarifa->adicional_m3,0,',','.') }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('tarifas.edit', $tarifa->id) }}" class="text-blue-600 hover:underline font-bold">Editar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
