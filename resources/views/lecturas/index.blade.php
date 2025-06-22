@extends('layouts.app')

@section('content')
<div class="w-full">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-display font-bold text-aquarius-800">Lecturas</h1>
        <a href="{{ route('lecturas.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-coral-500 text-white font-semibold shadow hover:bg-coral-600 transition">
            <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg>
            Nueva Lectura
        </a>
    </div>
    <form method="GET" class="mb-4 flex flex-wrap gap-4 items-end">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar usuario, matrícula, documento..." class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" />
        <label class="text-xs font-bold text-aquarius-700">Ordenar por:</label>
        <select name="orden" class="rounded border-aquarius-200 px-2 py-1 focus:ring-coral-400">
            <option value="anio" @selected(request('orden','anio')=='anio')>Año</option>
            <option value="ciclo" @selected(request('orden')=='ciclo')>Ciclo</option>
            <option value="matricula" @selected(request('orden')=='matricula')>Matrícula</option>
        </select>
        <select name="direccion" class="rounded border-aquarius-200 px-2 py-1 focus:ring-coral-400">
            <option value="desc" @selected(request('direccion','desc')=='desc')>Descendente</option>
            <option value="asc" @selected(request('direccion')=='asc')>Ascendente</option>
        </select>
        <button class="px-4 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Buscar</button>
    </form>
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-aquarius-100 text-aquarius-800 border-l-4 border-aquarius-400 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="min-w-full divide-y divide-aquarius-200 bg-white">
            <thead class="bg-aquarius-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">
                        <a href="?{{ http_build_query(array_merge(request()->all(), ['orden'=>'matricula','direccion'=>request('orden')=='matricula'&&request('direccion')=='asc'?'desc':'asc'])) }}" class="hover:underline">Matrícula</a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Usuario</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Medidor</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">
                        <a href="?{{ http_build_query(array_merge(request()->all(), ['orden'=>'anio','direccion'=>request('orden')=='anio'&&request('direccion')=='asc'?'desc':'asc'])) }}" class="hover:underline">Año</a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">
                        <a href="?{{ http_build_query(array_merge(request()->all(), ['orden'=>'ciclo','direccion'=>request('orden')=='ciclo'&&request('direccion')=='asc'?'desc':'asc'])) }}" class="hover:underline">Ciclo</a>
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Lectura anterior</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Lectura actual</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Consumo (m³)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-aquarius-100">
                @forelse($lecturas as $lectura)
                <tr class="hover:bg-aquarius-50 transition">
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->matricula }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->usuario ? $lectura->usuario->nombres . ' ' . $lectura->usuario->apellidos : '' }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->numero_serie }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->anio }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->ciclo }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->fecha }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->lectura_anterior }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->lectura_actual }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $lectura->consumo_m3 }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-6 text-aquarius-400">No hay lecturas registradas.</td>
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