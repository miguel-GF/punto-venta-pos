<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocenteController;
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
  Route::prefix('productos')->group(function () {
    Route::get('agregar', [ProductoController::class, 'agregarProductoView'])->name('agregar.productos');
    Route::post('agregar', [ProductoController::class, 'agregar']);
    Route::get('editar/{id}', [ProductoController::class, 'editarProductoView'])->name('editar.productos');
    Route::post('editar/{id}', [ProductoController::class, 'editar']);
    Route::post('eliminar/{id}', [ProductoController::class, 'eliminar']);
  });
});

// Ruta de fallback para redireccionar a la página de inicio de sesión
Route::get('/{any}', [ViewController::class, 'loginView'])->where('any', '.*')->middleware('login');