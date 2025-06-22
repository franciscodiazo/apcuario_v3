<?php
namespace App\Http\Controllers;

use App\Models\Medidors;
use App\Models\Usuario;
use Illuminate\Http\Request;

class MedidorController extends Controller
{
    public function index()
    {
        $medidors = Medidors::with('usuario')->get();
        return view('medidores.index', compact('medidors'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        return view('medidores.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required|exists:usuarios,matricula',
            'numero_serie' => 'required|unique:medidors,numero_serie',
        ]);
        Medidors::create($request->all());
        return redirect()->route('medidores.index')->with('success', 'Medidor creado correctamente.');
    }
}