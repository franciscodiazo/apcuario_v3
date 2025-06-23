@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Créditos del usuario</h2>
    {{-- Formulario de selección de usuario/matrícula --}}
    <form method="GET" action="{{ route('creditos.index') }}" class="mb-6 flex gap-2 items-end">
        <div>
            <label class="block text-xs font-bold text-aquarius-700 mb-1">Buscar usuario o matrícula</label>
            <input type="text" name="matricula" value="{{ request('matricula') }}" placeholder="Matrícula o nombre" class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" required />
        </div>
        <button class="px-4 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Buscar</button>
    </form>
    @if($usuario)
        <div class="mb-4 p-4 bg-blue-50 rounded">
            <div><span class="font-bold">Nombre:</span> {{ $usuario->nombres }} {{ $usuario->apellidos }}</div>
            <div><span class="font-bold">Matrícula:</span> {{ $usuario->matricula }}</div>
        </div>
        {{-- Resumen de créditos --}}
        <div class="mb-4 flex gap-6 items-center">
            <div class="bg-cyan-100 rounded px-4 py-2 text-cyan-900 font-bold">Cantidad de créditos: {{ $creditos->count() }}</div>
            <div class="bg-cyan-100 rounded px-4 py-2 text-cyan-900 font-bold">Total créditos: ${{ number_format($creditos->sum('valor'), 0) }}</div>
        </div>
        {{-- Formulario para crear nuevo crédito --}}
        <form method="POST" action="{{ route('creditos.store') }}" class="mb-6 flex flex-wrap gap-4 items-end bg-cyan-50 p-4 rounded border border-cyan-200">
            @csrf
            <input type="hidden" name="usuario_id" value="{{ $usuario->id }}">
            <input type="hidden" name="matricula" value="{{ $usuario->matricula }}">
            <div>
                <label class="block text-xs font-bold text-aquarius-700 mb-1">Valor del crédito</label>
                <input type="number" name="valor" min="1" step="1" class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-aquarius-700 mb-1">Fecha</label>
                <input type="date" name="fecha" class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" value="{{ date('Y-m-d') }}" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-aquarius-700 mb-1">Acuerdo/Contrato</label>
                <input type="text" name="acuerdo" class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" placeholder="N° o referencia" required />
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-aquarius-700 mb-1">Detalle</label>
                <input type="text" name="detalle" class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400 w-full" placeholder="Detalle del crédito" required />
            </div>
            <button class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold shadow hover:bg-green-800 transition">Registrar crédito</button>
        </form>
        @if($creditos->count() > 0)
            <table class="w-full mb-4 text-sm border">
                <thead class="bg-cyan-100">
                    <tr>
                        <th class="p-2">Valor</th>
                        <th class="p-2">Saldo</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2">Fecha</th>
                        <th class="p-2">Acuerdo/Contrato</th>
                        <th class="p-2">Detalle</th>
                        <th class="p-2">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($creditos as $credito)
                        <tr class="border-b">
                            <td class="p-2">${{ number_format($credito->valor, 0) }}</td>
                            <td class="p-2">${{ number_format($credito->saldo, 0) }}</td>
                            <td class="p-2">{{ ucfirst($credito->estado) }}</td>
                            <td class="p-2">{{ $credito->fecha ?? ($credito->created_at ? $credito->created_at->format('Y-m-d') : '') }}</td>
                            <td class="p-2">{{ $credito->acuerdo ?? '' }}</td>
                            <td class="p-2">{{ $credito->detalle ?? '' }}</td>
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
        <div class="p-4 bg-yellow-50 rounded text-yellow-700 font-bold mb-4">Busca un usuario por matrícula o nombre para registrar un crédito.</div>
    @endif
</div>
@endsection
