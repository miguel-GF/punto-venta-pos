<?php

namespace App\Repos\Data;

use App\Repos\RH\VentaRH;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class VentaRepoData
{
  /**
   * Listar ventas
   *
   * @param  mixed $datos []
   * @return array
   */
  public static function listarVentas(array $filtros)
  {
    try {
      $query = DB::table('ventas as  v')
        ->select(
          'v.*',
          'u.nombre as usuario_nombre'
        )
        ->join('usuarios as u', 'u.usuario_id', 'v.registro_autor_id');

      VentaRH::obtenerFiltrosListarVentas($query, $filtros);

      return $query->get()->toArray();
    } catch (QueryException $qe) {
      throw $qe;
    }
  }

  /**
   * Listar ventas detalle por venta id
   *
   * @param  mixed $ventaDetalleId
   * @return array
   */
  public static function obtenerVentasDetallePorId($ventaDetalleId)
  {
    try {
      $query = DB::table('ventas_detalle as  vd')
        ->select(
          'vd.*',
          'u.nombre as usuario_nombre'
        )
        ->join('usuarios as u', 'u.usuario_id', 'vd.registro_autor_id')
        ->where('vd.venta_id', $ventaDetalleId)
        ->orderBy('vd.venta_detalle_id');

      return $query->get()->toArray();
    } catch (QueryException $qe) {
      throw $qe;
    }
  }
}
