<?php

namespace App\Services\Data;

use App\Repos\Data\ClienteRepoData;

class ClienteServiceData
{
  /**
   * listar productos
   *
   * @param  mixed $filtros
   * @return array
   */
  public static function listarBasico(array $filtros)
  {
    return ClienteRepoData::listarBasico($filtros);
  }
}
