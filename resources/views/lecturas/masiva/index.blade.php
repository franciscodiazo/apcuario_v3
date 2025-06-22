@extends('layouts.app')
@section('content')
<div class="w-full">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-display font-bold text-aquarius-800">Usuarios Registrados</h1>
        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-coral-500 text-white font-semibold shadow hover:bg-coral-600 transition">
            <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg>
            Registrar usuario
        </a>
    </div>
    @if(isset($usuarios) && $usuarios->isEmpty())
        <div class="mb-4 px-4 py-3 rounded-lg bg-sand-100 text-aquarius-800 border-l-4 border-coral-400 animate-fade-in">
            No hay usuarios registrados.
        </div>
    @elseif(isset($usuarios))
        <div class="overflow-x-auto rounded-xl shadow">
            <table class="min-w-full divide-y divide-aquarius-200 bg-white">
                <thead class="bg-aquarius-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Matrícula</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Apellidos</th>
                        <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Nombres</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-aquarius-100">
                    @foreach($usuarios as $usuario)
                    <tr class="hover:bg-aquarius-50 transition">
                        <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->matricula }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->apellidos }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->nombres }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6 flex justify-center">
            {{ $usuarios->links('pagination::tailwind') }}
        </div>
    @else
        <div class="mb-4 px-4 py-3 rounded-lg bg-coral-100 text-coral-800 border-l-4 border-coral-400 animate-fade-in">No se pudo cargar la lista de usuarios.</div>
    @endif
</div>
@endsection