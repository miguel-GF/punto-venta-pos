<?php

namespace App\Repos\RH;

use App\OrderConstants;

class ProductoRH
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
    if (!empty($filtros['productoId'])) {
      $query->where("p.producto_id", [strtolower($filtros['productoId'])]);
    }

    if (!empty($filtros['status'])) {
      $query->whereRaw("LOWER(p.status) = ?", [strtolower($filtros['status'])]);
    }

    if (!empty($filtros['busqueda'])) {
      $busqueda = strtoupper($filtros['busqueda']);
      $query->whereRaw("UPPER(p.clave) LIKE ?", ["%$busqueda%"])
        ->orWhereRaw("UPPER(p.codigo_barras) LIKE ?", ["%$busqueda%"])
        ->orWhereRaw("UPPER(p.nombre) LIKE ?", ["%$busqueda%"])
        ->orWhereRaw("UPPER(p.descripcion) LIKE ?", ["%$busqueda%"]);
    }

    if (!empty($filtros['ordenar'])) {
      switch ($filtros['ordenar']) {
        case OrderConstants::NOMBRE_ASC:
          $query->orderBy('p.nombre');
          break;
        case OrderConstants::NOMBRE_DESC:
          $query->orderByDesc('p.nombre');
          break;
        default:
          $query->orderBy('p.nombre');
          break;
      }
    }
  }
}
