<?php
namespace App\Http\Controllers;

use App\Models\Credito;
use App\Models\Usuario;
use Illuminate\Http\Request;

class CreditoController extends Controller
{
    public function index(Request $request)
    {
        $matricula = $request->input('matricula');
        $usuario = null;
        if ($matricula) {
            $usuario = Usuario::where('matricula', $matricula)
                ->orWhere('nombres', 'like', "%$matricula%")
                ->orWhere('apellidos', 'like', "%$matricula%")
                ->first();
        }
        $creditos = $usuario ? $usuario->creditos : collect();
        return view('creditos.index', compact('usuario', 'creditos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'matricula' => 'required',
            'valor' => 'required|numeric|min:1',
            'fecha' => 'required|date',
            'acuerdo' => 'required|string',
            'detalle' => 'required|string',
        ]);
        $credito = Credito::create([
            'usuario_id' => $request->usuario_id,
            'matricula' => $request->matricula,
            'valor' => $request->valor,
            'saldo' => $request->valor,
            'estado' => 'pendiente',
            'fecha' => $request->fecha,
            'acuerdo' => $request->acuerdo,
            'detalle' => $request->detalle,
        ]);
        return redirect()->route('creditos.index', ['matricula' => $request->matricula])
            ->with('success', 'Crédito registrado correctamente.');
    }

    public function abonar(Request $request, $id)
    {
        $credito = Credito::findOrFail($id);
        $abono = floatval($request->input('abono'));
        if ($abono > 0 && $abono <= $credito->saldo) {
            $credito->saldo -= $abono;
            if ($credito->saldo == 0) {
                $credito->estado = 'cancelado';
            } else {
                $credito->estado = 'abonado';
            }
            $credito->save();
            return back()->with('success', 'Abono realizado correctamente.');
        }
        return back()->with('error', 'El abono debe ser mayor a 0 y menor o igual al saldo.');
    }

    public function general()
    {
        $creditos = \App\Models\Credito::with('usuario')->orderByDesc('created_at')->get();
        $totalRecaudado = \App\Models\Credito::where('estado', 'cancelado')->sum('valor');
        $totalPendiente = \App\Models\Credito::where('estado', '!=', 'cancelado')->sum('saldo');
        return view('creditos.general', compact('creditos', 'totalRecaudado', 'totalPendiente'));
    }
}
