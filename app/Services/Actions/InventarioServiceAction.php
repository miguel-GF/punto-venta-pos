<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Utils;
use Illuminate\Support\Facades\DB;
use stdClass;

class InventarioServiceAction
{
  /**
   * agregar
   *
   * @param  mixed $datos [busqueda, tipoMovimiento, cantidad, fechaActual]
   * @return mixed
   */
  public static function agregar(array $datos)
  {
    try {
      DB::beginTransaction();

      $producto = Producto::where('codigo_barras', $datos['busqueda'])->get()->first();
      if (empty($producto)) {
        $producto = Producto::where('clave', $datos['busqueda'])->get()->first();
      }

      if (empty($producto)) {
        return 300;
      }

      if ($datos['tipoMovimiento'] == Constants::SALIDA_MOVIMIENTO_INVENTARIO_TIPO) {
        $cantidad = $datos['cantidad'] * -1;
      } else {
        $cantidad = $datos['cantidad'];
      }

      // Crear un nuevo objeto MovimientoInventario
      $datosComentario = new stdClass();
      $datosComentario->cantidad = $cantidad;
      $datosComentario->nombre = $producto->nombre;
      $datosComentario->clave = strtoupper($producto->clave);
      $movimientoInventario = new MovimientoInventario([
        'producto_id' => $producto->producto_id,
        'tipo' => $datos['tipoMovimiento'],
        'cantidad' => $cantidad,
        'registro_autor_id' => Utils::getUserId(),
        'registro_fecha' => $datos['fechaActual'],
        'comentario' => MovimientoInventario::ponerComentario(
          $datos['tipoMovimiento'],
          $datosComentario,
        ),
      ]);

      $movimientoInventario->save();

      // Se actualiza stock del producto
      ProductoServiceAction::actualizarStockService([
        'productoId' => $producto->producto_id,
        'cantidad' => $cantidad,
        'fechaActual' => $datos['fechaActual'],
      ]);

      // Verificamos que no haya quedado en negativo
      $productoActual = Producto::where('producto_id', $producto->producto_id)->get()->first();
      if ($productoActual->existencia < 0) {
        DB::rollBack();
        return 301;
      }

      DB::commit();

      return 200;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }
}
