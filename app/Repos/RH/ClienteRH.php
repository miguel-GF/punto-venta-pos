<?php

namespace App\Repos\RH;

use App\OrderConstants;

class ClienteRH
{
  /**
   * obtenerFiltrosListarBasico
   *
   * @param  mixed $query
   * @param  mixed $filtros
   * @return void
   */
  public static function obtenerFiltrosListarBasico(&$query, array $filtros)
  {
    if (!empty($filtros['status'])) {
      $query->whereRaw("LOWER(c.status) = ?", [strtolower($filtros['status'])]);
    }

    if (!empty($filtros['ordenar'])) {
      switch ($filtros['ordenar']) {
        case OrderConstants::NOMBRE_ASC:
          $query->orderBy('c.nombre_comercial');
          break;
        case OrderConstants::NOMBRE_DESC:
          $query->orderByDesc('c.nombre');
          break;
        default:
          $query->orderBy('c.nombre');
          break;
      }
    }
  }
}
