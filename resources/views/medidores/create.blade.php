@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Nuevo Medidor</h1>
    <form action="{{ route('medidores.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Matrícula</label>
            <select name="matricula" class="form-control" required>
                <option value="">Seleccione</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->matricula }}">{{ $usuario->matricula }} - {{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3"><label>Número de Serie</label><input type="text" name="numero_serie" class="form-control" required></div>
        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('medidores.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection