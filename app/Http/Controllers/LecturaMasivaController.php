<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Lectura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturaMasivaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user || !($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'operador'))) {
            abort(403, 'No autorizado.');
        }
        $usuarios = Usuario::with('matricula')->get();
        return view('lecturas.masiva.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $matriculas = $request->input('lectura_actual', []);
        $ciclos = $request->input('ciclo', []);
        $anios = $request->input('anio', []);
        $guardados = 0;
        foreach ($matriculas as $matricula => $lectura_actual) {
            if ($lectura_actual !== null && $lectura_actual !== '') {
                // Buscar la última lectura anterior
                $lecturaAnterior = Lectura::where('matricula', $matricula)
                    ->orderByDesc('anio')
                    ->orderByDesc('ciclo')
                    ->first();
                $lectura_anterior = $lecturaAnterior ? $lecturaAnterior->lectura_actual : 0;
                $anio = isset($anios[$matricula]) && $anios[$matricula] ? $anios[$matricula] : (date('Y'));
                $ciclo = isset($ciclos[$matricula]) && $ciclos[$matricula] ? $ciclos[$matricula] : 1;
                Lectura::create([
                    'matricula' => $matricula,
                    'anio' => $anio,
                    'ciclo' => $ciclo,
                    'fecha' => now(),
                    'lectura_actual' => $lectura_actual,
                    'lectura_anterior' => $lectura_anterior,
                    'consumo_m3' => $lectura_actual - $lectura_anterior,
                ]);
                $guardados++;
            }
        }
        return redirect()->back()->with('success', $guardados.' lecturas registradas correctamente.');
    }

    public function show($id)
    {
       $lectura = Lectura::findOrFail($id);   
        return view('lecturas.show', compact('lectura'));
    
    }

}