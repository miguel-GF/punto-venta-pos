<?php

namespace App\Repos\Actions;

use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use stdClass;

class ProductoRepoAction
{
  /**
   * Actualizar stock producto
   *
   * @param  mixed $datos
   * @param  stdClass $producto
   * @return mixed
   */
  public static function actualizarStock(array $datos, stdClass $producto)
  {
    try {
      DB::table("productos")
      ->where("producto_id", $producto->producto_id)
      ->update([
        "existencia" => DB::raw("existencia - {$producto->cantidad}"),
        "actualizacion_fecha" => $datos["fechaActual"],
        "actualizacion_autor_id" => Utils::getUserId(),
      ]);
    } catch (QueryException $th) {
      throw $th;
    }
  }
}
