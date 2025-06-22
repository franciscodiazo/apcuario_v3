@extends('layouts.app')

@section('content')
<div class="w-full">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-display font-bold text-aquarius-800">Listado de Usuarios</h1>
    </div>
    <form method="GET" class="mb-4 flex flex-wrap gap-4 items-end">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar usuario, matrícula..." class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" />
        <button class="px-4 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Buscar</button>
    </form>
    <form action="{{ route('lecturas.masiva.store') }}" method="POST" id="form-lectura-masiva">
        @csrf
        <div class="flex flex-wrap gap-4 mb-6 items-end">
            <div>
                <label class="block text-xs font-bold text-aquarius-700 mb-1">Ciclo para todos</label>
                <input type="number" min="1" max="6" id="ciclo-masivo" class="w-24 rounded border-aquarius-200 text-center focus:ring-coral-400" placeholder="Ciclo" />
            </div>
            <div>
                <label class="block text-xs font-bold text-aquarius-700 mb-1">Año para todos</label>
                <input type="number" min="2020" max="2100" id="anio-masivo" class="w-32 rounded border-aquarius-200 text-center focus:ring-coral-400" placeholder="Año" />
            </div>
            <button type="button" id="btn-cargar-masivo" class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Cargar a todos</button>
        </div>
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="min-w-full divide-y divide-aquarius-200 bg-white">
                <thead class="bg-aquarius-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Usuario</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-aquarius-700 uppercase tracking-wider">Ciclo</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-aquarius-700 uppercase tracking-wider">Año</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-aquarius-700 uppercase tracking-wider">Lectura anterior</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-aquarius-700 uppercase tracking-wider">Lectura actual</th>
                        <th class="px-4 py-3 text-center text-xs font-bold text-aquarius-700 uppercase tracking-wider">Consumo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-aquarius-100">
                    @if($usuarios && count($usuarios))
                    @foreach($usuarios as $usuario)
                    @php
                        $ultimaLectura = $usuario->lecturas->first();
                        $lecturaAnterior = $ultimaLectura ? $ultimaLectura->lectura_actual : 0;
                        $ciclo = $ultimaLectura ? $ultimaLectura->ciclo : '';
                        $anio = $ultimaLectura ? $ultimaLectura->anio : '';
                    @endphp
                    <tr class="hover:bg-aquarius-50 transition">
                        <td class="px-4 py-2 whitespace-nowrap font-semibold">{{ $usuario->matricula }} - {{ $usuario->apellidos }} {{ $usuario->nombres }}</td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <input type="number" min="1" max="6" class="w-16 rounded border-aquarius-200 text-center focus:ring-coral-400 ciclo-input" name="ciclo[{{ $usuario->matricula }}]" value="{{ $ciclo }}" placeholder="Ciclo" required />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <input type="number" min="2020" max="2100" class="w-20 rounded border-aquarius-200 text-center focus:ring-coral-400 anio-input" name="anio[{{ $usuario->matricula }}]" value="{{ $anio }}" placeholder="Año" required />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <input type="number" class="w-20 rounded border-aquarius-200 bg-sand-50 text-center" value="{{ $lecturaAnterior }}" readonly tabindex="-1" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <input type="number" class="w-24 rounded border-aquarius-200 focus:ring-coral-400 lectura-actual" name="lectura_actual[{{ $usuario->matricula }}]" placeholder="Nueva" data-anterior="{{ $lecturaAnterior }}" />
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap text-center">
                            <input type="number" class="w-20 rounded border-aquarius-200 bg-sand-50 text-center consumo-calc" value="" readonly tabindex="-1" />
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center py-6 text-aquarius-400">No hay usuarios registrados.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $usuarios->links('pagination::tailwind') }}
        </div>
        <div class="mt-6 flex justify-end">
            <button type="submit" class="px-8 py-3 rounded-lg bg-coral-500 text-white font-bold shadow hover:bg-coral-600 transition">Guardar seleccionados</button>
        </div>
    </form>
</div>
@endsection
