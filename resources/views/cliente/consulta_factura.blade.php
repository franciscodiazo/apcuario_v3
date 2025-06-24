@extends('layouts.app')
@section('title', 'Consulta de Factura')
@section('content')
<div class="max-w-md mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-center">Consulta de Factura</h2>
    <form method="POST" action="{{ route('consulta.factura') }}" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label for="matricula" class="block text-sm font-medium">Matrícula</label>
            <input type="text" name="matricula" id="matricula" class="mt-1 block w-full border rounded px-3 py-2" required value="{{ old('matricula') }}">
        </div>
        <div>
            <label for="documento" class="block text-sm font-medium">Cédula</label>
            <input type="text" name="documento" id="documento" class="mt-1 block w-full border rounded px-3 py-2" required value="{{ old('documento') }}">
        </div>
        @if($errors->any())
            <div class="text-red-600 text-sm">{{ $errors->first() }}</div>
        @endif
        <button type="submit" class="w-full bg-aquarius-700 text-white py-2 rounded hover:bg-aquarius-800 transition">Consultar</button>
    </form>
</div>
@endsection
