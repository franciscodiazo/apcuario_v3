@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Nuevo Usuario</h1>
    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf
        <div class="mb-3"><label>Matrícula</label><input type="text" name="matricula" class="form-control" required></div>
        <div class="mb-3"><label>Documento</label><input type="text" name="documento" class="form-control" required></div>
        <div class="mb-3"><label>Apellidos</label><input type="text" name="apellidos" class="form-control" required></div>
        <div class="mb-3"><label>Nombres</label><input type="text" name="nombres" class="form-control" required></div>
        <div class="mb-3"><label>Correo</label><input type="email" name="correo" class="form-control"></div>
        <div class="mb-3"><label>Estrato</label><input type="text" name="estrato" class="form-control"></div>
        <div class="mb-3"><label>Celular</label><input type="text" name="celular" class="form-control"></div>
        <div class="mb-3"><label>Sector</label><input type="text" name="sector" class="form-control"></div>
        <div class="mb-3"><label>No. de Personas</label><input type="number" name="no_personas" class="form-control"></div>
        <div class="mb-3"><label>Dirección</label><input type="text" name="direccion" class="form-control" required></div>
        <button class="btn btn-success">Guardar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection