<?php
namespace App\Http\Controllers;

use App\Models\Medidors;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedidorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->roles->contains('name', 'admin')) {
            abort(403, 'No autorizado.');
        }
        $medidores = Medidors::paginate(20);
        return view('medidores.index', compact('medidores'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        return view('medidores.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:255|unique:medidors,numero',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);
        Medidors::create($validated);
        return redirect()->route('medidores.index')->with('success', 'Medidor creado correctamente.');
    }
}