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
    $fileName = $ventaArchivoPdf->nombre;

    $tempDir = sys_get_temp_dir();
    $tempFilePath = $tempDir . '/' . $fileName;
    file_put_contents($tempFilePath, $pdfContent);

     // Leer el archivo y codificarlo en base64
    $pdfArchivo = file_get_contents($tempFilePath);
    $pdfBase64 = base64_encode($pdfArchivo);

    $res = new stdClass();
    $res->nombre = $fileName;
    $res->base64 = $pdfBase64;

    if (file_exists($tempFilePath)) {
      unlink($tempFilePath);
    }
    
    return $res;
  }
}
