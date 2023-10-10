<?php

namespace App\Services\Data;

use App\Repos\Data\SucursalRepoData;

class SucursalServiceData
{
  /**
   * listar sucursales
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarBasico(array $filtros)
  {
    return SucursalRepoData::listarBasico($filtros);
  }
}
