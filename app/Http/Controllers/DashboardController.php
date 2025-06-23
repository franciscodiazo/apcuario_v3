<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Lectura;
use App\Models\Credito;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $usuariosCount = Usuario::count();
        $ultimoUsuario = Usuario::orderByDesc('created_at')->first();

        // Agrupar lecturas por año y ciclo
        $lecturasPorAnioCiclo = Lectura::selectRaw('anio, ciclo, COUNT(*) as total')
            ->groupBy('anio', 'ciclo')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->get();

        $ultimaLectura = Lectura::orderByDesc('fecha')->orderByDesc('created_at')->first();

        // Pagos totales
        $pagosTotales = 0;
        $pagos = Lectura::where('pagado', true)->get();
        foreach ($pagos as $l) {
            $base = 22000; $limite = 50; $adicional = 2500;
            if ($precio = \App\Models\Precio::where('anio', $l->anio)->first()) {
                $base = $precio->costo_base; $limite = $precio->limite_base; $adicional = $precio->costo_adicional;
            }
            $consumo = $l->consumo_m3;
            $pagosTotales += $consumo <= $limite ? $base : $base + ($consumo - $limite) * $adicional;
        }

        // Pagos por método
        $pagosPorMetodo = Lectura::where('pagado', true)
            ->selectRaw('metodo_pago, COUNT(*) as cantidad, SUM(CASE WHEN consumo_m3 <= IFNULL((SELECT limite_base FROM precios WHERE anio = lecturas.anio LIMIT 1),50) THEN IFNULL((SELECT costo_base FROM precios WHERE anio = lecturas.anio LIMIT 1),22000) ELSE IFNULL((SELECT costo_base FROM precios WHERE anio = lecturas.anio LIMIT 1),22000) + (consumo_m3-IFNULL((SELECT limite_base FROM precios WHERE anio = lecturas.anio LIMIT 1),50))*IFNULL((SELECT costo_adicional FROM precios WHERE anio = lecturas.anio LIMIT 1),2500) END) as total')
            ->groupBy('metodo_pago')
            ->get();

        // Pagos por mes y año
        $pagosPorMes = [];
        $pagos = Lectura::where('pagado', true)
            ->whereNotNull('fecha_pago')
            ->get();
        $pagosMesTmp = [];
        foreach ($pagos as $l) {
            $base = 22000; $limite = 50; $adicional = 2500;
            if ($precio = \App\Models\Precio::where('anio', $l->anio)->first()) {
                $base = $precio->costo_base; $limite = $precio->limite_base; $adicional = $precio->costo_adicional;
            }
            $consumo = $l->consumo_m3;
            $valor = $consumo <= $limite ? $base : $base + ($consumo - $limite) * $adicional;
            $anio = date('Y', strtotime($l->fecha_pago));
            $mes = date('m', strtotime($l->fecha_pago));
            $key = $anio.'-'.$mes;
            if (!isset($pagosMesTmp[$key])) {
                $pagosMesTmp[$key] = ['anio' => $anio, 'mes' => $mes, 'total' => 0];
            }
            $pagosMesTmp[$key]['total'] += $valor;
        }
        $pagosPorMes = array_values($pagosMesTmp);

        // Créditos
        $creditosCount = Credito::count();
        $creditosTotal = Credito::sum('valor');

        // Créditos pagados y pendientes (totales)
        $creditosPagados = Credito::where('estado', 'cancelado')->sum('valor');
        $creditosPendientes = Credito::where('estado', '!=', 'cancelado')->sum('saldo');

        // Facturas pagadas y pendientes (totales)
        $facturasPagadas = Lectura::where('pagado', true)->count();
        $facturasPendientes = Lectura::where('pagado', false)->count();

        // Totales por ciclo y año
        $facturasPorCiclo = Lectura::selectRaw('anio, ciclo, COUNT(*) as cantidad, SUM(CASE WHEN consumo_m3 <= IFNULL((SELECT limite_base FROM precios WHERE anio = lecturas.anio LIMIT 1),50) THEN IFNULL((SELECT costo_base FROM precios WHERE anio = lecturas.anio LIMIT 1),22000) ELSE IFNULL((SELECT costo_base FROM precios WHERE anio = lecturas.anio LIMIT 1),22000) + (consumo_m3-IFNULL((SELECT limite_base FROM precios WHERE anio = lecturas.anio LIMIT 1),50))*IFNULL((SELECT costo_adicional FROM precios WHERE anio = lecturas.anio LIMIT 1),2500) END + 5000) as total_facturas')
            ->groupBy('anio', 'ciclo')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->get();
        $recaudadoPorCiclo = Lectura::where('pagado', true)
            ->selectRaw('anio, ciclo, SUM(CASE WHEN consumo_m3 <= IFNULL((SELECT limite_base FROM precios WHERE anio = lecturas.anio LIMIT 1),50) THEN IFNULL((SELECT costo_base FROM precios WHERE anio = lecturas.anio LIMIT 1),22000) ELSE IFNULL((SELECT costo_base FROM precios WHERE anio = lecturas.anio LIMIT 1),22000) + (consumo_m3-IFNULL((SELECT limite_base FROM precios WHERE anio = lecturas.anio LIMIT 1),50))*IFNULL((SELECT costo_adicional FROM precios WHERE anio = lecturas.anio LIMIT 1),2500) END + 5000) as total_recaudado')
            ->groupBy('anio', 'ciclo')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->get();
        $creditosPorCiclo = Credito::selectRaw('YEAR(fecha) as anio, MONTH(fecha) as ciclo, COUNT(*) as cantidad, SUM(valor) as total_creditos')
            ->groupBy('anio', 'ciclo')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->get();
        $creditosPagadosPorCiclo = Credito::where('estado', 'cancelado')
            ->selectRaw('YEAR(fecha) as anio, MONTH(fecha) as ciclo, COUNT(*) as cantidad, SUM(valor) as total_creditos_pagados')
            ->groupBy('anio', 'ciclo')
            ->orderByDesc('anio')
            ->orderByDesc('ciclo')
            ->get();

        // Tarifa actual (último año)
        $tarifaActual = \App\Models\Tarifa::orderByDesc('anio')->first();

        return view('dashboard', compact(
            'usuariosCount',
            'ultimoUsuario',
            'lecturasPorAnioCiclo',
            'ultimaLectura',
            'pagosTotales',
            'pagosPorMetodo',
            'pagosPorMes',
            'creditosCount',
            'creditosTotal',
            'facturasPorCiclo',
            'recaudadoPorCiclo',
            'creditosPorCiclo',
            'creditosPagadosPorCiclo',
            'creditosPagados',
            'creditosPendientes',
            'facturasPagadas',
            'facturasPendientes',
            'tarifaActual'
        ));
    }
}