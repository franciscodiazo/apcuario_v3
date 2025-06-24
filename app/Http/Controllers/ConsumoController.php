<?php
namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Precio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsumoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user || !($user->roles->contains('name', 'admin') || $user->roles->contains('name', 'operador'))) {
            abort(403, 'No autorizado.');
        }
        $anio = $request->input('anio', date('Y'));
        $ciclo = $request->input('ciclo');
        $estado = $request->input('estado');
        $query = Lectura::with('usuario');
        if ($anio) $query->where('anio', $anio);
        if ($ciclo) $query->where('ciclo', $ciclo);
        if ($estado === 'pendiente') {
            $query->where('pagado', false);
        } elseif ($estado === 'pagado') {
            $query->where('pagado', true);
        }
        $lecturas = $query->orderByDesc('anio')->orderByDesc('ciclo')->paginate(10);
        $precios = Precio::where('anio', $anio)->first();
        return view('consumos.index', compact('lecturas', 'precios', 'anio', 'ciclo', 'estado'));
    }

    public function pagar(Request $request, $id)
    {
        $lectura = Lectura::findOrFail($id);
        $lectura->pagado = true;
        $lectura->metodo_pago = $request->input('metodo_pago');
        $lectura->fecha_pago = now();
        $lectura->save();
        return redirect()->back()->with('success', 'Pago registrado correctamente.');
    }

    public function recibo($id)
    {
        $lectura = Lectura::with('usuario')->findOrFail($id);
        $precios = Precio::where('anio', $lectura->anio)->first();
        return view('consumos.recibo', compact('lectura', 'precios'));
    }
}
