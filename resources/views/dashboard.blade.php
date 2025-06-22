@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <h1 class="text-3xl font-display font-bold text-aquarius-900 mb-8 tracking-tight">Dashboard</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-coral-100 to-coral-300 rounded-2xl shadow-lg p-6 flex flex-col items-center">
            <div class="text-5xl font-bold text-coral-700 mb-2">{{ $usuariosCount }}</div>
            <div class="text-lg font-semibold text-coral-900">Usuarios registrados</div>
        </div>
        <div class="md:col-span-2 bg-gradient-to-br from-aquarius-50 to-sand-100 rounded-2xl shadow-lg p-6">
            <div class="text-lg font-semibold text-aquarius-800 mb-2">Último usuario registrado</div>
            @if($ultimoUsuario)
                <div class="flex flex-col md:flex-row md:items-center md:gap-4">
                    <div class="text-xl font-bold text-aquarius-900">{{ $ultimoUsuario->nombres }} {{ $ultimoUsuario->apellidos }}</div>
                    <div class="text-sm text-aquarius-600">Matrícula: {{ $ultimoUsuario->matricula }}</div>
                    <div class="text-xs text-sand-700">Registrado: {{ $ultimoUsuario->created_at->format('Y-m-d H:i') }}</div>
                </div>
            @else
                <div class="text-aquarius-400">No hay usuarios registrados.</div>
            @endif
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
@endsection