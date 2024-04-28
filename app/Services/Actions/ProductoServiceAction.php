<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Utils;
use Illuminate\Support\Facades\DB;
use stdClass;

class ProductoServiceAction
{
  /**
   * agregar
   *
   * @param  mixed $datos [clave, codigoBarras, nombre, descripcion, precio, existencia, fechaActual]
   * @return bool
   */
  public static function agregar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      $existe = Producto::where('clave', $datos['clave'])->exists();

      if ($existe) {
        return false;
      }

      $conExistenciaInicial = false;
      if ($datos['existencia'] > 0) {
        $conExistenciaInicial = true;
      }

      // Crear un nuevo insert de producto
      $insertProducto = [
        'clave' => strtoupper($datos['clave']),
        'codigo_barras' => $datos['codigoBarras'],
        'nombre' => $datos['nombre'],
        'descripcion' => $datos['descripcion'],
        'precio' => $datos['precio'],
        'existencia' => $datos['existencia'],
        'con_existencia_inicial' => $conExistenciaInicial,
        'registro_autor_id' => Utils::getUserId(),
        'registro_fecha' => $datos['fechaActual'],
        'marca' => $datos['marca'] ?? null,
        'familia' => $datos['familia'] ?? null,
      ];
      $productoId = DB::table('productos')->insertGetId($insertProducto, 'producto_id');

      // Si hay existencia se agrega mov inv inicial
      if ($conExistenciaInicial) {
        $datosComentario = new stdClass();
        $datosComentario->cantidad = $datos['existencia'];
        $datosComentario->nombre = $datos['nombre'];
        $datosComentario->clave = strtoupper($datos['clave']);
        $movimientoInventario = new MovimientoInventario([
          'producto_id' => $productoId,
          'tipo' => Constants::ENTRADA_MOVIMIENTO_INVENTARIO_TIPO,
          'cantidad' => $datos['existencia'],
          'registro_autor_id' => Utils::getUserId(),
          'registro_fecha' => $datos['fechaActual'],
          'comentario' => MovimientoInventario::ponerComentario(
            Constants::INICIAL_MOVIMIENTO_INVENTARIO_TIPO,
            $datosComentario,
          ),
        ]);
        $movimientoInventario->save();
      }

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  /**
   * editar
   *
   * @param  mixed $datos [productoId, clave, codigoBarras, nombre, descripcion, precio, existencia, fechaActual]
   * @return bool
   */
  public static function editar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      $existe = Producto::where('clave', $datos['clave'])->where('producto_id', '<>', $datos['productoId']) ->exists();

      if ($existe) {
        return false;
      }

      // Buscar el producto existente por su id
      $producto = Producto::find($datos['productoId']);
      
      if ($datos['existencia'] > 0 && $producto->existencia <= 0) {
        $producto->con_existencia_inicial = true;
        $datosComentario = new stdClass();
        $datosComentario->cantidad = $datos['existencia'];
        $datosComentario->nombre = $datos['nombre'];
        $datosComentario->clave = strtoupper($datos['clave']);
        $movimientoInventario = new MovimientoInventario([
          'producto_id' => $datos['productoId'],
          'tipo' => Constants::ENTRADA_MOVIMIENTO_INVENTARIO_TIPO,
          'cantidad' => $datos['existencia'],
          'registro_autor_id' => Utils::getUserId(),
          'registro_fecha' => $datos['fechaActual'],
          'comentario' => MovimientoInventario::ponerComentario(
            Constants::INICIAL_MOVIMIENTO_INVENTARIO_TIPO,
            $datosComentario,
          ),
        ]);
        $movimientoInventario->save();
      }
      // Actualizar los campos del producto
      $producto->clave = strtoupper($datos['clave']);
      $producto->codigo_barras = $datos['codigoBarras'];
      $producto->nombre = $datos['nombre'];
      $producto->descripcion = $datos['descripcion'];

      $producto->precio = $datos['precio'];
      $producto->existencia = $datos['existencia'];
      $producto->actualizacion_autor_id = Utils::getUserId();
      $producto->actualizacion_fecha = $datos['fechaActual'];
      $producto->marca = $datos['marca'] ?? null;
      $producto->familia = $datos['familia'] ?? null;

      $producto->save();

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  /**
   * editar
   *
   * @param  mixed $datos [productoId, fechaActual]
   * @return bool
   */
  public static function eliminar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      // Buscar el producto existente por su id
      $producto = Producto::find($datos['productoId']);

      // Eliminamos el producto
      $producto->status = Constants::BAJA_STATUS;
      $producto->actualizacion_autor_id = Utils::getUserId();
      $producto->actualizacion_fecha = $datos['fechaActual'];

      $producto->save();

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }
  
  /**
   * actualizarStockService
   *
   * @param  mixed $datos [productoId, cantidad]
   * @return bool
   */
  public static function actualizarStockService(array $datos): bool
  {
    try {

      // Buscar el producto existente por su id
      $producto = Producto::find($datos['productoId']);

      // Se actualiza stock
      $producto->existencia = $producto->existencia + $datos['cantidad'];
      $producto->actualizacion_autor_id = Utils::getUserId();
      $producto->actualizacion_fecha = $datos['fechaActual'];

      $producto->save();

      return true;
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
