<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Precio;

class PrecioController extends Controller
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
        $precios = Precio::paginate(20);
        return view('precios.index', compact('precios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'concepto' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);
        Precio::create($validated);
        return redirect()->route('precios.index')->with('success', 'Precio registrado correctamente.');
    }

    // ...existing code...
}