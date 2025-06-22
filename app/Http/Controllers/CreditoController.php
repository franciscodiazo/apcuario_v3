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
        $usuario = Usuario::where('matricula', $matricula)->first();
        $creditos = $usuario ? $usuario->creditos : collect();
        return view('creditos.index', compact('usuario', 'creditos'));
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
}
