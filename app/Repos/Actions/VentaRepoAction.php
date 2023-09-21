<?php

namespace App\Repos\Actions;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class VentaRepoAction
{
  /**
   * Agregar nueva venta
   *
   * @param  mixed $datos
   * @return mixed
   */
  public static function agregar(array $datos)
  {
    try {
      return DB::table('ventas')
        ->insertGetId($datos, 'venta_id');
    } catch (QueryException $th) {
      throw $th;
    }
  }

  /**
   * Agregar venta detalle
   *
   * @param  mixed $datos
   * @return void
   */
  public static function agregarDetalle(array $datos)
  {
    try {
      DB::table('ventas_detalle')
        ->insert($datos);
    } catch (QueryException $th) {
      throw $th;
    }
  }
}
