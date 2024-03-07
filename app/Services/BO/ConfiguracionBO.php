<?php

namespace App\Services\BO;

use App\Utils;
use stdClass;

class ConfiguracionBO
{
  /**
   * armarInsertVenta
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarUpdateSucursalDefault(array $datos): array
  {
    $update = [];
    $update['nombre'] = strtoupper($datos['nombre']);
    $update['direccion'] = strtoupper($datos['direccion']);
    $update['telefono'] = $datos['telefono'] ?? null;
    $update['rfc'] = $datos['rfc'] ?? null;
    $update['ticket_leyenda_pie'] = $datos['ticketLeyendaPie'] ?? null;
    $update['actualizacion_autor_id'] = Utils::getUserId();
    $update['actualizacion_fecha'] = $datos['fechaActual'];

    return $update;
  }
}
