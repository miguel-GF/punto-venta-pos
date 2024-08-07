<?php

namespace App\Repos\Data;

use Illuminate\Support\Facades\DB;

class InventarioRepoData
{
  /**
   * Listar movimientos de inventarios
   *
   * @param  mixed $datos []
   * @return array
   */
  public static function listar(array $datos)
  {
    return DB::table('movimientos_inventario as mi')
      ->select(
        'mi.movimiento_inventario_id',
        'mi.tipo',
        'mi.cantidad',
        'mi.registro_fecha',
        'p.clave',
        'p.codigo_barras',
        'p.nombre',
        'p.marca',
        'p.familia',
        DB::raw("
          CASE
            WHEN mi.venta_id IS NOT NULL THEN v.serie_folio ELSE '--'
          END AS modulo_folio")
      )
      ->join('productos as p', 'p.producto_id', 'mi.producto_id')
      ->leftJoin('ventas as v', 'v.venta_id', 'mi.venta_id')
      ->whereDate('mi.registro_fecha', now()->format('Y-m-d'))
      ->orderByDesc('mi.registro_fecha')
      ->get()
      ->toArray();
  }
}
