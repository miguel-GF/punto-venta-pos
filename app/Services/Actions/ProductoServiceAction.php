<?php

namespace App\Services\Actions;

use App\Models\Producto;
use App\Utils;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductoServiceAction
{
  /**
   * loginDocente
   *
   * @param  mixed $datos [clave, codigoBarras, nombre, descripcion, precio, existencia]
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
}
