<?php

namespace App\Repos\Actions;

use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use stdClass;

class SucursalRepoAction
{
  /**
   * Actualizar sucursal
   *
   * @param  mixed $datos
   * @param  $sucursalId
   * @return mixed
   */
  public static function actualizar(array $datos, $sucursalId)
  {
    DB::table("sucursales")
    ->where("sucursal_id", $sucursalId)
    ->update($datos);
  }
}
