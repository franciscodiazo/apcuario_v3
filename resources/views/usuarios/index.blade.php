@extends('layouts.app')

@section('content')
<div class="w-full">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h1 class="text-2xl font-display font-bold text-aquarius-800">Usuarios</h1>
        <a href="{{ route('usuarios.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-coral-500 text-white font-semibold shadow hover:bg-coral-600 transition">
            <svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4'/></svg>
            Nuevo Usuario
        </a>
    </div>
    <form method="GET" class="mb-4 flex flex-wrap gap-4 items-end">
        <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Buscar usuario, matrícula, documento..." class="rounded border-aquarius-200 px-3 py-2 focus:ring-coral-400" />
        <button class="px-4 py-2 rounded-lg bg-aquarius-700 text-white font-semibold shadow hover:bg-coral-600 transition">Buscar</button>
    </form>
    @if(session('success'))
        <div class="mb-4 px-4 py-3 rounded-lg bg-aquarius-100 text-aquarius-800 border-l-4 border-aquarius-400 animate-fade-in">
            {{ session('success') }}
        </div>
    @endif
    <div class="overflow-x-auto rounded-xl shadow">
        <table class="min-w-full divide-y divide-aquarius-200 bg-white">
            <thead class="bg-aquarius-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Matrícula</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Documento</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Apellidos</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Nombres</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Correo</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Estrato</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Celular</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Sector</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">No. Personas</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Dirección</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-aquarius-700 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-aquarius-100">
                @forelse($usuarios as $usuario)
                <tr class="hover:bg-aquarius-50 transition">
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->matricula }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->documento }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->apellidos }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->nombres }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->correo }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->estrato }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->celular }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->sector }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->no_personas }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $usuario->direccion }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-800 transition">Editar</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center py-6 text-aquarius-400">No hay usuarios registrados.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $usuarios->links('pagination::tailwind') }}
    </div>
</div>
@endsection