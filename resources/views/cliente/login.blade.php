@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto mt-12 p-8 bg-white/90 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-coral-700 mb-6 text-center">Consulta de Factura y Créditos</h2>
    <form method="POST" action="{{ route('cliente.login') }}" class="flex flex-col gap-4">
        @csrf
        <label class="block text-sm font-bold text-aquarius-700">Matrícula</label>
        <input type="text" name="matricula" class="rounded-lg border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-sm" required autofocus value="{{ old('matricula') }}" />
        <label class="block text-sm font-bold text-aquarius-700">Documento</label>
        <input type="text" name="documento" class="rounded-lg border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-sm" required value="{{ old('documento') }}" />
        <button type="submit" class="w-full py-3 rounded-lg bg-coral-600 text-white font-bold text-xl shadow hover:bg-coral-800 transition">Ingresar</button>
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
