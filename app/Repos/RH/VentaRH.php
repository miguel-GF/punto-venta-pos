<?php

namespace App\Repos\RH;

use App\OrderConstants;

class VentaRH
{
  /**
   * obtenerFiltrosListarBasico
   *
   * @param  mixed $query
   * @param  mixed $filtros
   * @return void
   */
  public static function obtenerFiltrosListarVentas(&$query, array $filtros)
  {
    if (!empty($filtros['ventaId'])) {
      $query->where("v.venta_id", $filtros['ventaId']);
    }

    if (!empty($filtros['status'])) {
      $query->whereRaw("LOWER(v.status) = ?", [strtolower($filtros['status'])]);
    }

    if (!empty($filtros['ordenar'])) {
      switch ($filtros['ordenar']) {
        case OrderConstants::FOLIO_ASC:
          $query->orderBy('v.folio');
          break;
        case OrderConstants::FOLIO_DESC:
          $query->orderByDesc('v.folio');
          break;
        default:
          $query->orderBy('v.folio');
          break;
      }
    }
  }
}
