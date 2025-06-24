<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Role;

class UsuarioController extends Controller
{
    // Seguridad: Proteger con middleware auth y rol
    public function __construct()
    {
        $this->middleware('auth');
        // Si se reactiva el middleware de rol, agregar aquí
    }

    public function index(Request $request)
    {
        // Seguridad: Permitir admin y operador
        $user = Auth::user();
        if (!$user || !($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'operador'))) {
            abort(403, 'No autorizado.');
        }
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
        $roles = Role::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id',
        ]);
        $usuario = Usuario::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol_id' => $validated['rol_id'],
        ]);
        $usuario->roles()->attach($validated['rol_id']);
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