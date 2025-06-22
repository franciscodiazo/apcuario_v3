@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Créditos del usuario</h2>
    @if($usuario)
        <div class="mb-4 p-4 bg-blue-50 rounded">
            <div><span class="font-bold">Nombre:</span> {{ $usuario->nombres }} {{ $usuario->apellidos }}</div>
            <div><span class="font-bold">Matrícula:</span> {{ $usuario->matricula }}</div>
        </div>
        @if($creditos->count() > 0)
            <table class="w-full mb-4 text-sm border">
                <thead class="bg-cyan-100">
                    <tr>
                        <th class="p-2">Valor</th>
                        <th class="p-2">Saldo</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($creditos as $credito)
                        <tr class="border-b">
                            <td class="p-2">${{ number_format($credito->valor, 0) }}</td>
                            <td class="p-2">${{ number_format($credito->saldo, 0) }}</td>
                            <td class="p-2">{{ ucfirst($credito->estado) }}</td>
                            <td class="p-2">
                                @if($credito->saldo > 0)
                                <form method="POST" action="{{ route('creditos.abonar', $credito->id) }}" class="flex gap-2 items-center">
                                    @csrf
                                    <input type="number" name="abono" min="1" max="{{ $credito->saldo }}" class="w-20 border rounded px-2 py-1" placeholder="Abonar">
                                    <button class="px-3 py-1 bg-green-600 text-white rounded">Abonar</button>
                                </form>
                                @else
                                    <span class="text-green-700 font-bold">Pagado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-4 bg-green-50 rounded text-green-700 font-bold">¡Felicidades! No tienes créditos pendientes.</div>
        @endif
    @else
        <div class="p-4 bg-red-50 rounded text-red-700 font-bold">Usuario no encontrado.</div>
    @endif
</div>
@endsection
