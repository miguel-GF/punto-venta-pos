<?php

namespace App\Services\Actions;

use App\Models\Producto;
use App\Utils;
use Illuminate\Support\Facades\DB;
use Exception;

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

      // Crear un nuevo objeto Producto
      $producto = new Producto([
        'clave' => $datos['clave'],
        'codigo_barras' => $datos['codigoBarras'],
        'nombre' => $datos['nombre'],
        'descripcion' => $datos['descripcion'],
        'precio' => $datos['precio'],
        'existencia' => $datos['existencia'],
        'registro_autor_id' => Utils::getUserId(),
        'registro_fecha' => $datos['fechaActual'],
      ]);

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
   * @param  mixed $datos [productoId, clave, codigoBarras, nombre, descripcion, precio, existencia, fechaActual]
   * @return bool
   */
  public static function editar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      $existe = Producto::where('clave', $datos['clave'])->where('id', '<>', $datos['productoId']) ->exists();

      if ($existe) {
        return false;
      }

      // Buscar el producto existente por su id
      $producto = Producto::find($datos['productoId']);

      // Actualizar los campos del producto
      $producto->clave = $datos['clave'];
      $producto->codigo_barras = $datos['codigoBarras'];
      $producto->nombre = $datos['nombre'];
      $producto->descripcion = $datos['descripcion'];
      $producto->precio = $datos['precio'];
      $producto->existencia = $datos['existencia'];
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
}
