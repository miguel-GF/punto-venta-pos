<?php

namespace App\Services\Data;

use App\Repos\Data\ProductoRepoData;

class ProductoServiceData
{
  /**
   * listar productos
   *
   * @param  mixed $datos []
   * @return array
   */
  public static function listarBasico(array $datos)
  {
    return ProductoRepoData::listarBasico($datos);
  }
}
