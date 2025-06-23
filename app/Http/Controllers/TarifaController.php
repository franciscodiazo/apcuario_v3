<?php
namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function index()
    {
        $tarifas = \App\Models\Tarifa::orderByDesc('anio')->get();
        if ($tarifas->isEmpty()) {
            return redirect()->route('tarifas.create')->with('info', 'Primero debe registrar una tarifa.');
        }
        return view('tarifas.index', compact('tarifas'));
    }

    public function create()
    {
        return view('tarifas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer|min:2000|max:2100|unique:tarifas,anio',
            'basico' => 'required|integer|min:0',
            'adicional_m3' => 'required|integer|min:0',
        ]);
        Tarifa::create($request->only(['anio','basico','adicional_m3']));
        return redirect()->route('tarifas.index')->with('success','Tarifa registrada correctamente.');
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
