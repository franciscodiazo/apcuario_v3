@extends('layouts.app')
@section('content')
<div class="max-w-3xl mx-auto mt-8 p-6 bg-white/90 rounded-xl shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-coral-700">
            @php $user = Auth::user(); @endphp
            @if($user && $user->hasRole('admin'))
                Bienvenido Administrador, {{ $usuario->nombres }} {{ $usuario->apellidos }}
            @elseif($user && $user->hasRole('operador'))
                Bienvenido Operador, {{ $usuario->nombres }} {{ $usuario->apellidos }}
            @else
                Bienvenido, {{ $usuario->nombres }} {{ $usuario->apellidos }}
            @endif
        </h2>
    </div>
    <div class="mb-8">
        <h3 class="text-xl font-bold text-aquarius-800 mb-2">Facturas/Consumos</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs md:text-sm border border-coral-200 rounded-lg">
                <thead class="bg-coral-100">
                    <tr>
                        <th class="px-3 py-2">Año</th>
                        <th class="px-3 py-2">Ciclo</th>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Lectura</th>
                        <th class="px-3 py-2">Consumo m³</th>
                        <th class="px-3 py-2">Pagado</th>
                        <th class="px-3 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lecturas as $lectura)
                    <tr class="border-t">
                        <td class="px-3 py-1">{{ $lectura->anio }}</td>
                        <td class="px-3 py-1">{{ $lectura->ciclo }}</td>
                        <td class="px-3 py-1">{{ $lectura->fecha }}</td>
                        <td class="px-3 py-1">{{ $lectura->lectura_actual }}</td>
                        <td class="px-3 py-1">{{ $lectura->consumo_m3 }}</td>
                        <td class="px-3 py-1">
                            @if($lectura->pagado)
                                <span class="text-green-700 font-bold">Sí</span>
                            @else
                                <span class="text-red-700 font-bold">No</span>
                            @endif
                        </td>
                        <td class="px-3 py-1 text-center">
                            <a href="{{ route('cliente.factura.ver', ['lecturaId' => $lectura->id, 'matricula' => $usuario->matricula, 'documento' => $usuario->documento]) }}" class="inline-block px-3 py-1 bg-blue-600 text-white rounded-lg font-bold text-xs hover:bg-blue-800 transition" title="Ver factura">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> Ver
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div>
        <h3 class="text-xl font-bold text-aquarius-800 mb-2">Créditos</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-xs md:text-sm border border-cyan-200 rounded-lg">
                <thead class="bg-cyan-100">
                    <tr>
                        <th class="px-3 py-2">Fecha</th>
                        <th class="px-3 py-2">Valor</th>
                        <th class="px-3 py-2">Saldo</th>
                        <th class="px-3 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($creditos as $c)
                    <tr class="border-t">
                        <td class="px-3 py-1">{{ $c->fecha }}</td>
                        <td class="px-3 py-1">${{ number_format($c->valor,0,',','.') }}</td>
                        <td class="px-3 py-1">${{ number_format($c->saldo,0,',','.') }}</td>
                        <td class="px-3 py-1">
                            @if($c->estado === 'cancelado')
                                <span class="text-green-700 font-bold">Pagado</span>
                            @else
                                <span class="text-yellow-700 font-bold">Pendiente</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
