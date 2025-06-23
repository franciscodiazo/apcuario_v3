@extends('layouts.app')
@section('content')
<div class="max-w-md mx-auto mt-4 p-2 sm:p-6 bg-white/90 rounded-2xl shadow-2xl border border-aquarius-100 backdrop-blur-md">
    <h2 class="text-3xl font-extrabold mb-7 text-center text-coral-700 tracking-tight drop-shadow">Registro de Lectura<br><span class="text-base text-aquarius-700 font-normal">(Modo Móvil)</span></h2>
    <form method="GET" class="mb-7 flex gap-2">
        <input type="text" name="matricula" value="{{ request('matricula') }}" placeholder="Buscar matrícula o nombre" class="flex-1 rounded-xl border border-coral-200 px-5 py-4 text-xl focus:ring-coral-400 shadow-md bg-white/80 placeholder:text-coral-400" autofocus required />
        <button class="px-6 py-4 rounded-xl bg-coral-500 text-white font-extrabold text-xl shadow-lg hover:bg-coral-700 transition-all">Buscar</button>
    </form>
    @if($usuario)
    <div class="mb-7 p-5 bg-blue-50/80 rounded-xl shadow flex flex-col gap-2 animate-fade-in border border-blue-100">
        <div class="font-extrabold text-coral-700 text-xl flex items-center gap-2">
            <svg class="w-6 h-6 text-coral-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            {{ $usuario->nombres }} {{ $usuario->apellidos }}
        </div>
        <div class="text-sm text-aquarius-700">Matrícula: <span class="font-semibold">{{ $usuario->matricula }}</span></div>
        <div class="text-sm text-aquarius-700">Dirección: <span class="font-semibold">{{ $usuario->direccion }}</span></div>
        @if($ultimaLectura)
        <div class="mt-2 text-sm text-cyan-800">Último ciclo registrado: <span class="font-semibold">Año {{ $ultimaLectura->anio }}, Ciclo {{ $ultimaLectura->ciclo }}</span></div>
        <div class="text-sm text-cyan-800">Lectura anterior: <span class="font-semibold" id="lectura-anterior">{{ $ultimaLectura->lectura_actual }}</span></div>
        @else
        <div class="mt-2 text-sm text-cyan-800">No hay lecturas previas para este usuario.</div>
        <div class="text-sm text-cyan-800">Lectura anterior: <span class="font-semibold" id="lectura-anterior">0</span></div>
        @endif
    </div>
    <form method="POST" action="{{ route('lecturas.movil.store') }}" class="bg-white/95 rounded-xl shadow-lg p-5 flex flex-col gap-5 border border-cyan-100 animate-fade-in">
        @csrf
        <input type="hidden" name="matricula" value="{{ $usuario->matricula }}">
        <input type="hidden" id="lectura-anterior-hidden" name="lectura_anterior" value="{{ $ultimaLectura ? $ultimaLectura->lectura_actual : 0 }}">
        <div class="flex gap-2">
            <div class="flex-1">
                <label class="block text-sm font-bold text-aquarius-700">Año</label>
                <input type="number" name="anio" min="2020" max="2100" class="rounded-xl border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-md focus:border-coral-400 transition-all duration-150 bg-white/80 w-full" value="{{ $ultimaLectura ? $ultimaLectura->anio : date('Y') }}" required />
            </div>
            <div class="flex-1">
                <label class="block text-sm font-bold text-aquarius-700">Ciclo</label>
                <select name="ciclo" class="rounded-xl border border-coral-200 px-4 py-3 text-lg focus:ring-coral-400 shadow-md focus:border-coral-400 transition-all duration-150 bg-white/80 w-full" required>
                    @for($i=1;$i<=6;$i++)
                        <option value="{{ $i }}" @if($ultimaLectura && $i == ($ultimaLectura->ciclo < 6 ? $ultimaLectura->ciclo+1 : 1)) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <label class="block text-sm font-bold text-aquarius-700">Lectura actual</label>
        <input type="number" name="lectura_actual" id="lectura-actual" class="rounded-xl border border-coral-200 px-5 py-4 text-xl focus:ring-coral-400 shadow-md focus:border-coral-400 transition-all duration-150 bg-white/80" required autofocus />
        <label class="block text-sm font-bold text-aquarius-700">Fecha</label>
        <input type="date" name="fecha" class="rounded-xl border border-coral-200 px-5 py-4 text-xl focus:ring-coral-400 shadow-md focus:border-coral-400 transition-all duration-150 bg-white/80" value="{{ date('Y-m-d') }}" required />
        <div class="text-base text-green-800 font-bold" id="consumo-info" style="display:none;">Consumo: <span id="consumo-value"></span> m³</div>
        <button type="submit" class="w-full py-4 rounded-xl bg-green-600 text-white font-extrabold text-2xl shadow-lg hover:bg-green-800 transition-all active:scale-95">Registrar</button>
        @if($errors->any())
            <div class="mt-2 p-2 bg-red-100 text-red-700 rounded text-base font-semibold text-center animate-shake">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
    </form>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const lecturaActual = document.getElementById('lectura-actual');
        const lecturaAnterior = document.getElementById('lectura-anterior-hidden');
        const consumoInfo = document.getElementById('consumo-info');
        const consumoValue = document.getElementById('consumo-value');
        if (lecturaActual) {
            lecturaActual.addEventListener('input', function() {
                const anterior = parseInt(lecturaAnterior.value) || 0;
                const actual = parseInt(lecturaActual.value) || 0;
                const consumo = actual - anterior;
                if (!isNaN(consumo) && consumo >= 0) {
                    consumoValue.textContent = consumo;
                    consumoInfo.style.display = 'block';
                } else {
                    consumoInfo.style.display = 'none';
                }
            });
        }
    });
    </script>
    @endif
    @if(session('success'))
        <div class="mt-7 p-4 bg-green-100 text-green-800 rounded-xl text-center text-xl font-extrabold shadow animate-fade-in">{{ session('success') }}</div>
    @endif
</div>
<style>
@keyframes fade-in { from { opacity: 0; transform: translateY(10px);} to { opacity: 1; transform: none;}}
.animate-fade-in { animation: fade-in 0.5s; }
@keyframes shake { 10%, 90% { transform: translateX(-1px); } 20%, 80% { transform: translateX(2px); } 30%, 50%, 70% { transform: translateX(-4px); } 40%, 60% { transform: translateX(4px); } }
.animate-shake { animation: shake 0.4s; }
</style>
@endsection
