<?php

namespace App\Services\Data;

use App\Repos\Data\InventarioRepoData;

class InventarioServiceData
{
  /**
   * listar movimientos inventarios
   *
   * @param  mixed $datos []
   * @return array
   */
  public static function listar(array $datos)
  {
    return InventarioRepoData::listar($datos);
  }
}
