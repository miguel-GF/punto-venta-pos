<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ViewController;
use Illuminate\Support\Facades\Route;

// login
Route::get('/login', [ViewController::class, 'loginView'])->middleware('login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::prefix('docente')->middleware('sys')->group(function () {
  Route::get('dashboard', [ViewController::class, 'docenteDashboardView'])->name('docente.dashboard');
  Route::get('cargasAcademicas', [DocenteController::class, 'docenteCargasAcademicasView'])->name('docente.cargas.academicas');
  Route::get('pasarAsistencias/{claveMateria}', [DocenteController::class, 'docentePasarAsistenciasCargasAcademicasView'])->name('docente.pasar.asistencias');
  Route::post('pasarAsistencias', [DocenteController::class, 'pasarAsistencias']);
});

Route::middleware('sys')->group(function () {
  Route::get('dashboard', [ViewController::class, 'docenteDashboardView'])->name('dashboard');
  // PRODUCTOS
  Route::prefix('productos')->group(function () {
    Route::get('/', [ProductoController::class, 'productosView'])->name('productos');
    Route::get('agregar', [ProductoController::class, 'agregarProductoView'])->name('agregar.productos');
    Route::post('agregar', [ProductoController::class, 'agregar']);
    Route::get('editar/{id}', [ProductoController::class, 'editarProductoView'])->name('editar.productos');
    Route::post('editar/{id}', [ProductoController::class, 'editar']);
    Route::post('eliminar/{id}', [ProductoController::class, 'eliminar']);
  });
  // CLIENTES
  Route::prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'clientesView'])->name('clientes');
    Route::get('agregar', [ClienteController::class, 'agregarClienteView'])->name('agregar.clientes');
    Route::post('agregar', [ClienteController::class, 'agregar']);
    Route::get('editar/{id}', [ClienteController::class, 'editarClienteView'])->name('editar.clientes');
    Route::post('editar/{id}', [ClienteController::class, 'editar']);
    Route::post('eliminar/{id}', [ClienteController::class, 'eliminar']);
  });
  // INVENTARIO
  Route::prefix('inventarios')->group(function () {
    Route::get('agregar', [InventarioController::class, 'agregarMovimientoInventarioView'])->name('agregar.inventario');
    Route::post('agregar', [InventarioController::class, 'agregar']);
  });
});

// Ruta de fallback para redireccionar a la página de inicio de sesión
Route::get('/{any}', [ViewController::class, 'loginView'])->where('any', '.*')->middleware('login');