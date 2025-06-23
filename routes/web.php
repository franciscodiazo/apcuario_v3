<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MedidorController;
use App\Http\Controllers\LecturaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LecturaMasivaController;
use App\Http\Controllers\ReporteController;

// Rutas para lecturas en modo móvil (deben ir antes de resource para evitar conflictos)
Route::match(['get', 'post'], 'lecturas/movil', [LecturaController::class, 'movil'])->name('lecturas.movil');
Route::post('lecturas/movil/store', [LecturaController::class, 'movilStore'])->name('lecturas.movil.store');

// Rutas públicas para login cliente
Route::get('/cliente/login', [App\Http\Controllers\ClienteController::class, 'loginForm'])->name('cliente.login');
Route::post('/cliente/login', [App\Http\Controllers\ClienteController::class, 'login']);

// Rutas de autenticación estándar (admin/operador)
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Ruta principal: redirige según autenticación y rol
Route::match(['get', 'head'], '/', function () {
    if (auth()->check()) {
        if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('operador')) {
            return redirect()->route('dashboard');
        } elseif (auth()->user()->hasRole('cliente')) {
            return redirect()->route('cliente.panel');
        }
    }
    return redirect()->route('login');
});

// Fallback para métodos no soportados en /
Route::any('/', function () {
    return redirect()->route('login');
});

// Rutas protegidas para admin y operador
Route::middleware(['auth'])->group(function () {
    // Dashboard y módulos protegidos solo para usuarios autenticados y con rol adecuado
    Route::get('/dashboard', function() {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('operador')))) {
            return redirect()->route('login');
        }
        return app(\App\Http\Controllers\DashboardController::class)->index();
    })->name('dashboard');

    // Módulos protegidos solo para admin/operador
    $adminOperadorCheck = function ($request, $next) {
        $user = auth()->user();
        if (!$user || !(method_exists($user, 'hasRole') && ($user->hasRole('admin') || $user->hasRole('operador')))) {
            return redirect()->route('login');
        }
        return $next($request);
    };
    Route::group(['middleware' => $adminOperadorCheck], function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::resource('medidores', MedidorController::class);
        Route::resource('lecturas', LecturaController::class);
        Route::get('/api/ultima-lectura', [LecturaController::class, 'ultimaLectura']);
        Route::get('/lecturas/masiva/{id}', [LecturaMasivaController::class, 'show'])->name('lecturas.masiva.show');
        Route::get('/lecturas/masiva', [LecturaMasivaController::class, 'index'])->name('lecturas.masiva.index');
        Route::post('/lecturas/masiva', [LecturaMasivaController::class, 'store'])->name('lecturas.masiva.store');
        Route::get('usuarios-listado', [UsuarioController::class, 'listado'])->name('usuarios.listado');
        Route::get('consumos', [\App\Http\Controllers\ConsumoController::class, 'index'])->name('consumos.index');
        Route::post('consumos/{id}/pagar', [\App\Http\Controllers\ConsumoController::class, 'pagar'])->name('consumos.pagar');
        Route::get('consumos/{id}/recibo', [\App\Http\Controllers\ConsumoController::class, 'recibo'])->name('consumos.recibo');
        Route::get('facturas/masiva', [\App\Http\Controllers\FacturaMasivaController::class, 'index'])->name('facturas.masiva');
        Route::get('/creditos', [App\Http\Controllers\CreditoController::class, 'index'])->name('creditos.index');
        Route::post('/creditos', [App\Http\Controllers\CreditoController::class, 'store'])->name('creditos.store');
        Route::post('/creditos/abonar/{id}', [App\Http\Controllers\CreditoController::class, 'abonar'])->name('creditos.abonar');
        Route::get('/creditos/general', [App\Http\Controllers\CreditoController::class, 'general'])->name('creditos.general');
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/pdf', [ReporteController::class, 'exportarPdf'])->name('reportes.exportarPdf');
        Route::get('/reportes/anual', [ReporteController::class, 'anual'])->name('reportes.anual');
        Route::get('/reportes/anual/pdf', [ReporteController::class, 'anualPdf'])->name('reportes.anualPdf');
        Route::get('/tarifas', [App\Http\Controllers\TarifaController::class, 'index'])->name('tarifas.index');
        Route::get('/tarifas/create', [App\Http\Controllers\TarifaController::class, 'create'])->name('tarifas.create');
        Route::post('/tarifas', [App\Http\Controllers\TarifaController::class, 'store'])->name('tarifas.store');
        Route::get('/tarifas/{id}/edit', [App\Http\Controllers\TarifaController::class, 'edit'])->name('tarifas.edit');
        Route::post('/tarifas/{id}', [App\Http\Controllers\TarifaController::class, 'update'])->name('tarifas.update');
    });
});

// Rutas de consulta cliente (sin autenticación)
Route::get('/cliente/panel', [App\Http\Controllers\ClienteController::class, 'panel'])->name('cliente.panel');
Route::post('/cliente/logout', [App\Http\Controllers\ClienteController::class, 'logout'])->name('cliente.logout');
Route::get('/cliente/factura/{lecturaId}/pdf', [App\Http\Controllers\ClienteController::class, 'descargarFactura'])->name('cliente.factura.pdf');
Route::get('/cliente/factura/{lecturaId}/ver', [App\Http\Controllers\ClienteController::class, 'verFactura'])->name('cliente.factura.ver');