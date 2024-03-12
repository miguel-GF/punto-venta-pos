<?php

namespace App\Services\Data;

use App\Constants;
use App\Repos\Data\VentaRepoData;
use Illuminate\Support\Facades\DB;
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

  /**
   * obtenerArchivoPdf
   *
   * @param  mixed $ventaId
   * @return stdClass
   */
  public static function obtenerArchivoPdf($ventaId): stdClass
  {
    $ventaArchivoPdf = DB::table('ventas_archivos')->select('*')
      ->where('venta_id', $ventaId)
      ->where('status', Constants::ACTIVO_STATUS)
      ->get()->first();
    $pdfContent = $ventaArchivoPdf->archivo;
    $filePath = public_path($ventaArchivoPdf->nombre);
    file_put_contents($filePath, $pdfContent);
    $pdfArchivo = file_get_contents($filePath);
    $pdfBase64 = base64_encode($pdfArchivo);

    $res = new stdClass();
    $res->nombre = $ventaArchivoPdf->nombre;
    $res->base64 = $pdfBase64;
    return $res;
  }
}
