@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white/90 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-coral-700 mb-6">Nueva Tarifa</h2>
    <form method="POST" action="{{ route('tarifas.store') }}" class="flex flex-col gap-4">
        @csrf
        <label class="block text-sm font-bold text-aquarius-700">Año</label>
        <input type="number" name="anio" min="2020" max="2100" class="rounded-lg border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-sm" required value="{{ old('anio', date('Y')) }}" />
        <label class="block text-sm font-bold text-aquarius-700">Valor básico (COP)</label>
        <input type="number" name="basico" min="0" class="rounded-lg border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-sm" required value="{{ old('basico', 24000) }}" />
        <label class="block text-sm font-bold text-aquarius-700">Valor adicional por m³ (COP)</label>
        <input type="number" name="adicional_m3" min="0" class="rounded-lg border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-sm" required value="{{ old('adicional_m3', 2500) }}" />
        <button type="submit" class="w-full py-3 rounded-lg bg-green-600 text-white font-bold text-xl shadow hover:bg-green-800 transition">Registrar</button>
        @if($errors->any())
            <div class="mt-2 p-2 bg-red-100 text-red-700 rounded text-base font-semibold text-center animate-shake">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </form>
</div>
@endsection
