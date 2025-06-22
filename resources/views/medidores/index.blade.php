@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Medidores</h1>
    <a href="{{ route('medidores.create') }}" class="btn btn-primary mb-3">Nuevo Medidor</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Número de Serie</th>
                <th>Matrícula</th>
            </tr>
        </thead>
        <tbody>
            @foreach($medidors as $medidor)
            <tr>
                <td>{{ $medidor->numero_serie }}</td>
                <td>{{ $medidor->matricula }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection