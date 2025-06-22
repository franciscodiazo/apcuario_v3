@extends('layouts.app')
@section('content')
<div class="w-full max-w-2xl mx-auto">
    <h1 class="text-2xl font-display font-bold text-aquarius-800 mb-6">Nueva Lectura</h1>
    @if($usuarios->isEmpty())
        <div class="mb-4 px-4 py-3 rounded-lg bg-sand-100 text-aquarius-800 border-l-4 border-coral-400 animate-fade-in">
            No hay usuarios registrados. <a href="{{ route('usuarios.create') }}" class="inline-block ml-2 px-4 py-2 rounded bg-coral-500 text-white font-semibold hover:bg-coral-600 transition">Crear usuario</a>
        </div>
    @else
    <form action="{{ route('lecturas.store') }}" method="POST" id="lectura-form" class="space-y-5 bg-white rounded-xl shadow p-6">
        @csrf
        <div>
            <label class="block text-sm font-bold text-aquarius-700 mb-1">Matrícula</label>
            <select name="matricula" id="matricula" class="w-full rounded border-aquarius-200 focus:ring-coral-400" required>
                <option value="">Seleccione</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->matricula }}">{{ $usuario->matricula }} - {{ $usuario->nombres }} {{ $usuario->apellidos }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-bold text-aquarius-700 mb-1">Número de Medidor (opcional)</label>
            <input type="text" name="numero_serie" class="w-full rounded border-aquarius-200 focus:ring-coral-400" placeholder="Ingrese número de medidor si existe">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Año</label>
                <input type="number" name="anio" id="anio" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="{{ date('Y') }}" required readonly>
            </div>
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Ciclo</label>
                <input type="number" name="ciclo" id="ciclo" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="1" min="1" max="6" required readonly>
                <small class="text-xs text-aquarius-400">Ciclo del año (1 a 6). Se sugiere automáticamente según la última lectura.</small>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Fecha</label>
                <input type="date" name="fecha" id="fecha" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="{{ date('Y-m-d') }}" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Lectura anterior</label>
                <input type="number" id="lectura_anterior" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="0" readonly>
            </div>
        </div>
        <div>
            <label class="block text-sm font-bold text-aquarius-700 mb-1">Última lectura registrada</label>
            <div id="info_lectura_anterior" class="w-full rounded border bg-sand-50 px-3 py-2 min-h-[38px]"></div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Lectura actual</label>
                <input type="number" name="lectura_actual" id="lectura_actual" class="w-full rounded border-aquarius-200 focus:ring-coral-400" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-aquarius-700 mb-1">Consumo (calculado)</label>
                <input type="number" id="consumo_m3" class="w-full rounded border-aquarius-200 focus:ring-coral-400" value="0" readonly>
            </div>
        </div>
        <div class="flex gap-4 mt-4">
            <button class="px-6 py-2 rounded-lg bg-aquarius-700 text-white font-semibold hover:bg-aquarius-800 transition flex items-center gap-2">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'/></svg>
                Guardar
            </button>
            <a href="{{ route('lecturas.index') }}" class="px-6 py-2 rounded-lg bg-sand-400 text-white font-semibold hover:bg-sand-500 transition flex items-center gap-2">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12'/></svg>
                Cancelar
            </a>
        </div>
    </form>
    @endif
</div>
<script>
    document.getElementById('matricula').addEventListener('change', function() {
        let matricula = this.value;
        if(matricula) {
            fetch(`/api/ultima-lectura?matricula=${matricula}`)
                .then(response => response.json())
                .then(data => {
                    let ciclo = 1;
                    let anio = new Date().getFullYear();
                    if(data.anio && data.ciclo) {
                        if(parseInt(data.ciclo) < 6) {
                            ciclo = parseInt(data.ciclo) + 1;
                            anio = data.anio;
                        } else {
                            ciclo = 1;
                            anio = parseInt(data.anio) + 1;
                        }
                        document.getElementById('info_lectura_anterior').innerText =
                            `Lectura: ${data.lectura_actual} | Año: ${data.anio} | Ciclo: ${data.ciclo} | Fecha: ${data.fecha ?? ''}`;
                        document.getElementById('lectura_anterior').value = data.lectura_actual ?? 0;
                    } else {
                        document.getElementById('info_lectura_anterior').innerText = 'No hay lecturas anteriores para esta matrícula.';
                        document.getElementById('lectura_anterior').value = 0;
                    }
                    document.getElementById('anio').value = anio;
                    document.getElementById('ciclo').value = ciclo;
                    document.getElementById('fecha').value = new Date().toISOString().slice(0,10);
                    document.getElementById('lectura_actual').value = '';
                    calcularConsumo();
                })
                .catch(() => {
                    document.getElementById('anio').value = new Date().getFullYear();
                    document.getElementById('ciclo').value = 1;
                    document.getElementById('lectura_anterior').value = 0;
                    document.getElementById('info_lectura_anterior').innerText = '';
                    document.getElementById('lectura_actual').value = '';
                    document.getElementById('fecha').value = new Date().toISOString().slice(0,10);
                    calcularConsumo();
                });
        } else {
            document.getElementById('anio').value = new Date().getFullYear();
            document.getElementById('ciclo').value = 1;
            document.getElementById('lectura_anterior').value = 0;
            document.getElementById('info_lectura_anterior').innerText = '';
            document.getElementById('lectura_actual').value = '';
            document.getElementById('fecha').value = new Date().toISOString().slice(0,10);
            calcularConsumo();
        }
    });

    document.getElementById('lectura_actual').addEventListener('input', calcularConsumo);

    function calcularConsumo() {
        let anterior = parseInt(document.getElementById('lectura_anterior').value) || 0;
        let actual = parseInt(document.getElementById('lectura_actual').value) || 0;
        document.getElementById('consumo_m3').value = actual - anterior;
    }
</script>
@endsection