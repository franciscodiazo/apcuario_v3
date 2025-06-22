<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MedidorController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LecturaMasivaController;

use App\Models\Lectura;



Route::get('/', function () {
    return view('welcome');
});

Route::resource('usuarios', UsuarioController::class);
Route::resource('medidores', MedidorController::class);
Route::resource('lecturas', LecturaController::class);
Route::resource('lecturas', LecturaController::class)->except(['show']);
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
Route::post('/creditos/abonar/{id}', [App\Http\Controllers\CreditoController::class, 'abonar'])->name('creditos.abonar');