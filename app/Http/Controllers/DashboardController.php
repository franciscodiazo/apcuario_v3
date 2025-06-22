<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Lectura;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $usuariosCount = Usuario::count();
        $ultimoUsuario = Usuario::orderByDesc('created_at')->first();

        // Agrupar lecturas por año y ciclo
        $lecturasPorAnioCiclo = Lectura::selectRaw('anio, ciclo, COUNT(*) as total')
            ->groupBy('anio', 'ciclo')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->get();

        $ultimaLectura = Lectura::orderByDesc('fecha')->orderByDesc('created_at')->first();

        return view('dashboard', compact(
            'usuariosCount',
            'ultimoUsuario',
            'lecturasPorAnioCiclo',
            'ultimaLectura'
        ));
    }
}