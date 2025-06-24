<?php
namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Precio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Factura;

class FacturaMasivaController extends Controller
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
        $buscar = $request->input('buscar');
        $pagina = max(1, (int)$request->input('pagina', 1));
        $porPagina = 5;
        $query = Lectura::with(['usuario', 'ultimasLecturas']);
        if ($anio) $query->where('anio', $anio);
        if ($ciclo) $query->where('ciclo', $ciclo);
        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('matricula', 'like', "%$buscar%")
                  ->orWhereHas('usuario', function($qu) use ($buscar) {
                      $qu->where('nombres', 'like', "%$buscar%")
                         ->orWhere('apellidos', 'like', "%$buscar%")
                         ->orWhere('matricula', 'like', "%$buscar%") ;
                  });
            });
        }
        $lecturas = $query->orderBy('matricula')->get();
        // Pre-cargar precios por año para eficiencia
        $preciosPorAnio = \App\Models\Tarifa::whereIn('anio', $lecturas->pluck('anio')->unique())->get()->keyBy('anio');
        // Calcular valores de factura para cada lectura
        foreach ($lecturas as $lectura) {
            $precios = $preciosPorAnio[$lectura->anio] ?? null;
            $limite = $precios->basico ? 50 : 50;
            $basico = $precios->basico ?? 0;
            $adicional_m3 = $precios->adicional_m3 ?? 0;
            $consumo = $lectura->consumo_m3;
            $adicionales = max(0, $consumo - $limite);
            $valor_basico = $basico;
            $valor_adicional = $adicionales * $adicional_m3;
            $lectura->valor_basico = $valor_basico;
            $lectura->valor_adicional = $valor_adicional;
            $lectura->adicionales = $adicionales;
            $lectura->valor_factura = $valor_basico + $valor_adicional;
            $lectura->total_pagar = max(0, $lectura->valor_factura - ($lectura->usuario ? $lectura->usuario->creditos()->where('saldo', '>', 0)->sum('saldo') : 0));
        }
        // Paginación manual
        $total = $lecturas->count();
        $lecturas = $lecturas->slice(($pagina-1)*$porPagina, $porPagina);
        $totalPaginas = ceil($total / $porPagina);
        return view('facturas.masiva', compact('lecturas', 'preciosPorAnio', 'anio', 'ciclo', 'pagina', 'totalPaginas', 'total'));
    }
}
