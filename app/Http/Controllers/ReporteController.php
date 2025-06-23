<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lectura;
use App\Models\Credito;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $tipo = $request->input('tipo', 'diario');
        $fecha = $request->input('fecha', now()->toDateString());
        $anio = $request->input('anio', now()->year);
        $mes = $request->input('mes', now()->month);

        // Reporte de pagos de créditos
        $pagosCreditos = Credito::whereNotNull('updated_at')
            ->when($tipo === 'diario', fn($q) => $q->whereDate('updated_at', $fecha))
            ->when($tipo === 'mensual', fn($q) => $q->whereYear('updated_at', $anio)->whereMonth('updated_at', $mes))
            ->when($tipo === 'anual', fn($q) => $q->whereYear('updated_at', $anio))
            ->where('estado', 'cancelado')
            ->get();

        // Reporte de facturas pagadas
        $facturasPagadas = Lectura::where('pagado', true)
            ->when($tipo === 'diario', fn($q) => $q->whereDate('fecha_pago', $fecha))
            ->when($tipo === 'mensual', fn($q) => $q->whereYear('fecha_pago', $anio)->whereMonth('fecha_pago', $mes))
            ->when($tipo === 'anual', fn($q) => $q->whereYear('fecha_pago', $anio))
            ->get();

        // Gastos (si hay modelo de gastos)
        $gastos = collect();
        if (class_exists('App\\Models\\Gasto')) {
            $gastos = \App\Models\Gasto::when($tipo === 'diario', fn($q) => $q->whereDate('fecha', $fecha))
                ->when($tipo === 'mensual', fn($q) => $q->whereYear('fecha', $anio)->whereMonth('fecha', $mes))
                ->when($tipo === 'anual', fn($q) => $q->whereYear('fecha', $anio))
                ->get();
        }

        // Reporte anual: totales por mes
        $totalesPorMes = collect();
        if ($tipo === 'anual') {
            $totalesPorMes = Lectura::where('pagado', true)
                ->whereYear('fecha_pago', $anio)
                ->selectRaw('MONTH(fecha_pago) as mes, SUM(CASE WHEN consumo_m3 <= 50 THEN 22000 ELSE 22000 + (consumo_m3-50)*2500 + 5000 END) as total')
                ->groupBy('mes')
                ->orderBy('mes')
                ->get();
        }

        return view('reportes.index', compact('tipo', 'fecha', 'anio', 'mes', 'pagosCreditos', 'facturasPagadas', 'gastos', 'totalesPorMes'));
    }

    // Exportar PDF (usa dompdf o snappy)
    public function exportarPdf(Request $request)
    {
        $data = $this->index($request)->getData();
        $pdf = \PDF::loadView('reportes.pdf', (array)$data);
        return $pdf->download('reporte-'.$data['tipo'].'-'.now()->format('Ymd').'.pdf');
    }

    public function anual(Request $request)
    {
        $anio = $request->input('anio', now()->year);
        $totalesPorMes = \App\Models\Lectura::where('pagado', true)
            ->whereYear('fecha_pago', $anio)
            ->selectRaw('MONTH(fecha_pago) as mes, SUM(CASE WHEN consumo_m3 <= 50 THEN 22000 ELSE 22000 + (consumo_m3-50)*2500 + 5000 END) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        return view('reportes.anual', compact('anio', 'totalesPorMes'));
    }

    public function anualPdf(Request $request)
    {
        $anio = $request->input('anio', now()->year);
        $totalesPorMes = \App\Models\Lectura::where('pagado', true)
            ->whereYear('fecha_pago', $anio)
            ->selectRaw('MONTH(fecha_pago) as mes, SUM(CASE WHEN consumo_m3 <= 50 THEN 22000 ELSE 22000 + (consumo_m3-50)*2500 + 5000 END) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        $pdf = \PDF::loadView('reportes.anual_pdf', compact('anio', 'totalesPorMes'));
        return $pdf->download('reporte-anual-'.$anio.'-'.now()->format('Ymd').'.pdf');
    }
}
