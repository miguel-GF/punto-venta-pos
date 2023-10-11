<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\ConfiguracionController;
// use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// login
Route::get('/login', [ViewController::class, 'loginView'])->middleware('login')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('sys')->group(function () {
  Route::get('dashboard', [ViewController::class, 'dashboardView'])->name('dashboard');
  // PRODUCTOS
  Route::controller(ProductoController::class)->prefix('productos')->group(function () {
    Route::get('/', 'productosView')->name('productos');
    Route::get('/listar', 'listarProductos')->name('productosBusqueda');
    Route::get('/detalle/{busqueda}', 'obtenerProductoDetalle');
    Route::get('agregar', 'agregarProductoView')->name('agregar.productos');
    Route::post('agregar', 'agregar');
    Route::get('editar/{id}', 'editarProductoView')->name('editar.productos');
    Route::post('editar/{id}', 'editar');
    Route::post('eliminar/{id}', 'eliminar');
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
  // VENTAS
  Route::controller(VentaController::class)->prefix('ventas')->group(function () {
    Route::get('/', 'ventasView')->name('ventas');
    Route::get('detalle/{id}', 'ventaDetalleView')->name('ventaDetalle');
    Route::get('/agregar', 'agregarVentaView')->name('agregar.venta');
    Route::post('agregar', 'agregar');
  });
  // USUARIOS
  Route::controller(UsuarioController::class)->prefix('usuarios')->group(function () {
    Route::get('/', 'usuariosView')->name('usuarios');
    Route::get('/agregar', 'agregarUsuarioView')->name('agregar.usuario');
    Route::post('agregar', 'agregar');
    Route::get('editar/{id}', 'editarUsuarioView')->name('editar.usuarios');
    Route::post('editar/{id}', 'editar');
    Route::post('eliminar/{id}', 'eliminar');
  });
  // CONFIGURACIONES
  Route::controller(ConfiguracionController::class)->prefix('configuraciones')->group(function () {
    Route::get('usuario', 'configuracionUsuarioSesionView');
    Route::post('usuario', 'editarConfiguracionUsuarioSesion');
  });
  // TICKET
  // Route::controller(TicketController::class)->prefix('tickets')->group(function () {
  //   Route::get('imprimir/venta/{id}', 'imprimirVenta');
  // });
});

// Ruta de fallback para redireccionar a la página de inicio de sesión
Route::get('/{any}', [ViewController::class, 'loginView'])->where('any', '.*')->middleware('login');