<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::query();
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('matricula', 'like', "%$buscar%")
                  ->orWhere('documento', 'like', "%$buscar%")
                  ->orWhere('apellidos', 'like', "%$buscar%")
                  ->orWhere('nombres', 'like', "%$buscar%")
                  ->orWhere('correo', 'like', "%$buscar%")
                  ->orWhere('direccion', 'like', "%$buscar%") ;
            });
        }
        $usuarios = $query->orderByDesc('id')->paginate(10)->appends($request->all());
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'matricula' => 'required|unique:usuarios,matricula',
            'documento' => 'required|unique:usuarios,documento',
            'apellidos' => 'required',
            'nombres' => 'required',
            'correo' => 'nullable|email',
            'estrato' => 'nullable',
            'celular' => 'nullable',
            'sector' => 'nullable',
            'no_personas' => 'nullable|integer',
            'direccion' => 'required',
        ]);
        Usuario::create($request->all());
        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function listado(Request $request)
    {
        $query = \App\Models\Usuario::select('matricula', 'apellidos', 'nombres', 'id')
            ->with(['lecturas' => function($q) {
                $q->orderByDesc('anio')->orderByDesc('ciclo');
            }]);
        if ($request->filled('buscar')) {
            $buscar = $request->input('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('matricula', 'like', "%$buscar%")
                  ->orWhere('apellidos', 'like', "%$buscar%")
                  ->orWhere('nombres', 'like', "%$buscar%")
                  ->orWhere('id', 'like', "%$buscar%") ;
            });
        }
        $usuarios = $query->orderByDesc('id')->paginate(10)->appends($request->all());
        return view('usuarios.listado', compact('usuarios'));
    }
}