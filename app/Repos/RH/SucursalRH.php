<?php

namespace App\Repos\RH;

use App\OrderConstants;

class SucursalRH
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
      $query->whereRaw("LOWER(s.status) = ?", [strtolower($filtros['status'])]);
    }

    if (!empty($filtros['default'])) {
      $query->where("s.default", $filtros['default']);
    }

    if (!empty($filtros['ordenar'])) {
      switch ($filtros['ordenar']) {
        case OrderConstants::NOMBRE_ASC:
          $query->orderBy('s.nombre');
          break;
        case OrderConstants::NOMBRE_DESC:
          $query->orderByDesc('s.nombre');
          break;
        case OrderConstants::CLAVE_ASC:
          $query->orderBy('s.clave');
          break;
        case OrderConstants::CLAVE_DESC:
          $query->orderByDesc('s.clave');
          break;
        default:
          $query->orderBy('s.nombre');
          break;
      }
    }
  }
}
