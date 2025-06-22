<?php
namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Precio;
use Illuminate\Http\Request;

class FacturaMasivaController extends Controller
{
    public function index(Request $request)
    {
        $anio = $request->input('anio', date('Y'));
        $ciclo = $request->input('ciclo');
        $pagina = max(1, (int)$request->input('pagina', 1));
        $porPagina = 5;
        $query = Lectura::with(['usuario', 'ultimasLecturas']);
        if ($anio) $query->where('anio', $anio);
        if ($ciclo) $query->where('ciclo', $ciclo);
        $lecturas = $query->orderBy('matricula')->get();
        // Pre-cargar precios por año para eficiencia
        $preciosPorAnio = Precio::whereIn('anio', $lecturas->pluck('anio')->unique())->get()->keyBy('anio');
        // Paginación manual
        $total = $lecturas->count();
        $lecturas = $lecturas->slice(($pagina-1)*$porPagina, $porPagina);
        $totalPaginas = ceil($total / $porPagina);
        return view('facturas.masiva', compact('lecturas', 'preciosPorAnio', 'anio', 'ciclo', 'pagina', 'totalPaginas', 'total'));
    }
}
