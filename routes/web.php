<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MedidorController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LecturaMasivaController;
use App\Http\Controllers\ReporteController;

use App\Models\Lectura;



// Rutas para lecturas en modo movil (debe ir antes de resource para evitar conflictos)
Route::match(['get', 'post'], 'lecturas/movil', [LecturaController::class, 'movil'])->name('lecturas.movil');
Route::post('lecturas/movil/store', [LecturaController::class, 'movilStore'])->name('lecturas.movil.store');

Route::get('/', function () {
    return view('welcome');
});

Route::resource('usuarios', UsuarioController::class);
Route::resource('medidores', MedidorController::class);
Route::resource('lecturas', LecturaController::class);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/ultima-lectura', [LecturaController::class, 'ultimaLectura']);
Route::get('/lecturas/masiva/{id}', [LecturaMasivaController::class, 'show'])->name('lecturas.masiva.show');
Route::get('/lecturas/masiva', [LecturaMasivaController::class, 'index'])->name('lecturas.masiva.index');
Route::post('/lecturas/masiva', [LecturaMasivaController::class, 'store'])->name('lecturas.masiva.store');

Route::get('usuarios-listado', [UsuarioController::class, 'listado'])->name('usuarios.listado');

Route::get('/api/lectura-anterior', function (\Illuminate\Http\Request $request) {
    $lecturaAnterior = Lectura::where('matricula', $request->matricula)
        ->when($request->numero_serie, function($q) use ($request) {
            $q->where('numero_serie', $request->numero_serie);
        })
        ->where(function($q) use ($request) {
            $q->where('anio', '<', $request->anio)
              ->orWhere(function($q2) use ($request) {
                  $q2->where('anio', $request->anio)
                     ->where('ciclo', '<', $request->ciclo);
              });
        })
        ->orderByDesc('anio')
        ->orderByDesc('ciclo')
        ->first();

    return response()->json([
        'lectura_anterior' => $lecturaAnterior ? $lecturaAnterior->lectura_actual : 0
    ]);
});

Route::get('consumos', [\App\Http\Controllers\ConsumoController::class, 'index'])->name('consumos.index');
Route::post('consumos/{id}/pagar', [\App\Http\Controllers\ConsumoController::class, 'pagar'])->name('consumos.pagar');
Route::get('consumos/{id}/recibo', [\App\Http\Controllers\ConsumoController::class, 'recibo'])->name('consumos.recibo');
Route::get('facturas/masiva', [\App\Http\Controllers\FacturaMasivaController::class, 'index'])->name('facturas.masiva');

// Rutas para créditos
Route::get('/creditos', [App\Http\Controllers\CreditoController::class, 'index'])->name('creditos.index');
Route::post('/creditos', [App\Http\Controllers\CreditoController::class, 'store'])->name('creditos.store');
Route::post('/creditos/abonar/{id}', [App\Http\Controllers\CreditoController::class, 'abonar'])->name('creditos.abonar');
Route::get('/creditos/general', [App\Http\Controllers\CreditoController::class, 'general'])->name('creditos.general');

// Rutas para reportes
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('/reportes/pdf', [ReporteController::class, 'exportarPdf'])->name('reportes.exportarPdf');
Route::get('/reportes/anual', [ReporteController::class, 'anual'])->name('reportes.anual');
Route::get('/reportes/anual/pdf', [ReporteController::class, 'anualPdf'])->name('reportes.anualPdf');

// Rutas para tarifas
Route::get('/tarifas', [App\Http\Controllers\TarifaController::class, 'index'])->name('tarifas.index');
Route::get('/tarifas/create', [App\Http\Controllers\TarifaController::class, 'create'])->name('tarifas.create');
Route::post('/tarifas', [App\Http\Controllers\TarifaController::class, 'store'])->name('tarifas.store');
Route::get('/tarifas/{id}/edit', [App\Http\Controllers\TarifaController::class, 'edit'])->name('tarifas.edit');
Route::post('/tarifas/{id}', [App\Http\Controllers\TarifaController::class, 'update'])->name('tarifas.update');

// Módulo de consulta para usuario cliente
Route::get('/cliente/login', [App\Http\Controllers\ClienteController::class, 'loginForm'])->name('cliente.login');
Route::post('/cliente/login', [App\Http\Controllers\ClienteController::class, 'login']);
Route::get('/cliente/panel', [App\Http\Controllers\ClienteController::class, 'panel'])->name('cliente.panel');
Route::post('/cliente/logout', [App\Http\Controllers\ClienteController::class, 'logout'])->name('cliente.logout');
Route::get('/cliente/factura/{lecturaId}/pdf', [App\Http\Controllers\ClienteController::class, 'descargarFactura'])->name('cliente.factura.pdf');
Route::get('/cliente/factura/{lecturaId}/ver', [App\Http\Controllers\ClienteController::class, 'verFactura'])->name('cliente.factura.ver');