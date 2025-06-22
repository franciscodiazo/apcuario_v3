{{-- filepath: resources/views/lecturas/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="w-full max-w-2xl mx-auto">
    <h1 class="text-2xl font-display font-bold text-aquarius-800 mb-6">Editar Lectura</h1>
    <form action="{{ route('lecturas.update', $lectura) }}" method="POST" class="space-y-5 bg-white rounded-xl shadow p-6">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-bold text-aquarius-700 mb-1">Matrícula</label>
            <select name="matricula" class="w-full rounded border-aquarius-200 focus:ring-coral-400" required>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->matricula }}" @if($lectura->matricula == $usuario->matricula) selected @endif>
                        {{ $usuario->matricula }} - {{ $usuario->nombres }} {{ $usuario->apellidos }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-aquarius-700 mb-1">Número de Medidor</label>
            <select name="numero_serie" class="w-full rounded border-aquarius-200 focus:ring-coral-400" required>
                @foreach($medidores as $medidor)
                    <option value="{{ $medidor->numero_serie }}" @if($lectura->numero_serie == $medidor->numero_serie) selected @endif>
                        {{ $medidor->numero_serie }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Año</label>
                <input type="number" name="anio" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="{{ $lectura->anio }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Ciclo</label>
                <input type="number" name="ciclo" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="{{ $lectura->ciclo }}" required>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Fecha</label>
                <input type="date" name="fecha" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="{{ $lectura->fecha }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Consumo (m³)</label>
                <input type="number" name="consumo_m3" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="{{ $lectura->consumo_m3 }}" required>
            </div>
        </div>
        <div class="flex gap-4 mt-4">
            <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold hover:bg-aquarius-800 transition flex items-center gap-2">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg>
                Actualizar
            </button>
            <a href="{{ route('lecturas.index') }}" class="px-6 py-2 rounded-lg bg-sand-400 text-white font-semibold hover:bg-sand-500 transition flex items-center gap-2">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection