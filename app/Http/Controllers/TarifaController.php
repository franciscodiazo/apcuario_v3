<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarifa;

class TarifaController extends Controller
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
        $tarifas = Tarifa::paginate(20);
        return view('tarifas.index', compact('tarifas'));
    }

    public function create()
    {
        return view('tarifas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'valor' => 'required|numeric|min:0',
        ]);
        Tarifa::create($validated);
        return redirect()->route('tarifas.index')->with('success', 'Tarifa registrada correctamente.');
    }

    public function edit($id)
    {
        $tarifa = Tarifa::findOrFail($id);
        return view('tarifas.edit', compact('tarifa'));
    }

    public function update(Request $request, $id)
    {
        $tarifa = Tarifa::findOrFail($id);
        $request->validate([
            'anio' => 'required|integer|min:2000|max:2100|unique:tarifas,anio,'.$tarifa->id,
            'basico' => 'required|integer|min:0',
            'adicional_m3' => 'required|integer|min:0',
        ]);
        $tarifa->update($request->only(['anio','basico','adicional_m3']));
        return redirect()->route('tarifas.index')->with('success','Tarifa actualizada correctamente.');
    }
}
