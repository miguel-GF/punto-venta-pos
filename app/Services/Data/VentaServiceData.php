<?php

namespace App\Services\Data;

use App\Repos\Data\VentaRepoData;
use stdClass;
use Throwable;

class VentaServiceData
{
  /**
   * listar movimientos inventarios
   *
   * @param  mixed $datos []
   * @return array
   */
  public static function listarVentas(array $datos)
  {
    return VentaRepoData::listarVentas($datos);
  }

  /**
   * listar movimientos inventarios
   *
   * @param  mixed $ventaId
   * @return stdClass
   */
  public static function obtenerDetalle($ventaId)
  {
    try {
      $detalle = new stdClass();
      $ventas = VentaRepoData::listarVentas(['ventaId' => $ventaId]);
      $detalles = VentaRepoData::obtenerVentasDetallePorId($ventaId);
  
      $detalle->info = $ventas[0];
      $detalle->detalles = $detalles;
  
      return $detalle;
    } catch (Throwable $th) {
      throw $th;
    }
  }
}
