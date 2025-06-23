<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Lectura;
use App\Models\Credito;

class ClienteController extends Controller
{
    public function loginForm()
    {
        return view('cliente.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'matricula' => 'required',
            'documento' => 'required',
        ]);
        $usuario = Usuario::where('matricula', $request->matricula)
            ->where('documento', $request->documento)
            ->first();
        if (!$usuario) {
            return back()->withErrors(['matricula' => 'Datos incorrectos'])->withInput();
        }
        session(['cliente_id' => $usuario->id]);
        return redirect()->route('cliente.panel');
    }

    public function panel()
    {
        $usuario = Usuario::find(session('cliente_id'));
        if (!$usuario) return redirect()->route('cliente.login');
        $lecturas = Lectura::where('matricula', $usuario->matricula)->orderByDesc('anio')->orderByDesc('ciclo')->get();
        $creditos = Credito::where('usuario_id', $usuario->id)->orderByDesc('fecha')->get();
        return view('cliente.panel', compact('usuario', 'lecturas', 'creditos'));
    }

    public function logout()
    {
        session()->forget('cliente_id');
        return redirect()->route('cliente.login');
    }

    public function descargarFactura($lecturaId)
    {
        $lectura = \App\Models\Lectura::findOrFail($lecturaId);
        $usuario = \App\Models\Usuario::where('matricula', $lectura->matricula)->first();
        // Solo permitir si el usuario autenticado es dueño de la factura
        if (session('cliente_id') != $usuario->id) {
            abort(403);
        }
        $precios = \App\Models\Tarifa::where('anio', $lectura->anio)->first();
        $anio = $lectura->anio;
        $ciclo = $lectura->ciclo;
        $pagina = 1;
        $totalPaginas = 1;
        $lecturas = [$lectura];
        $preciosPorAnio = [$lectura->anio => $precios];
        $pdf = \PDF::loadView('facturas.masiva', [
            'lecturas' => $lecturas,
            'preciosPorAnio' => $preciosPorAnio,
            'anio' => $anio,
            'ciclo' => $ciclo,
            'pagina' => $pagina,
            'totalPaginas' => $totalPaginas,
            'soloFactura' => true
        ]);
        return $pdf->download('factura_'.$lectura->anio.'_ciclo_'.$lectura->ciclo.'.pdf');
    }

    public function verFactura($lecturaId)
    {
        $lectura = \App\Models\Lectura::findOrFail($lecturaId);
        $usuario = \App\Models\Usuario::where('matricula', $lectura->matricula)->first();
        if (session('cliente_id') != $usuario->id) {
            abort(403);
        }
        $precios = \App\Models\Tarifa::where('anio', $lectura->anio)->first();
        // Cálculo de valores según tarifa
        $consumo = $lectura->consumo_m3;
        $cargo_fijo = 5000;
        $otros_cargos = 0;
        $consumo_valor = 0;
        if ($precios) {
            $base = $precios->basico;
            $adicional = $precios->adicional_m3;
            $limite = 50; // Puedes ajustar si tienes un campo en tarifa
            if ($consumo <= $limite) {
                $consumo_valor = $base;
            } else {
                $consumo_valor = $base + ($consumo - $limite) * $adicional;
            }
        }
        $valor_factura = $consumo_valor + $cargo_fijo + $otros_cargos;
        // Últimas 3 lecturas para el gráfico
        $ultimas = \App\Models\Lectura::where('matricula', $lectura->matricula)
            ->orderByDesc('anio')->orderByDesc('ciclo')->limit(3)->get()->reverse();
        return view('cliente.factura_masiva', compact('lectura', 'usuario', 'precios', 'consumo_valor', 'cargo_fijo', 'otros_cargos', 'valor_factura', 'ultimas'));
    }
}
