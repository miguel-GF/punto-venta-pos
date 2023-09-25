<?php

namespace App\Services\BO;

use App\Utils;
use stdClass;

class VentaBO
{
  /**
   * armarInsertVenta
   *
   * @param  mixed $datos
   * @return array
   */
  public static function armarInsertVenta(array $datos): array
  {
    $insert = [];
    $insert['cliente_id'] = $datos['clienteId'];
    $insert['folio'] = $datos['folio'];
    $insert['serie_folio'] = "VEN" . $datos['folio'];
    $insert['total'] = $datos['totalVenta'];
    $insert['cantidad'] = $datos['numeroProductos'];
    $insert['registro_autor_id'] = Utils::getUserId();
    $insert['registro_fecha'] = $datos['fechaActual'];

    return $insert;
  }

  /**
   * armarInsertVentaDetalle
   *
   * @param  mixed $datos
   * @param  stdClass $producto
   * @return array
   */
  public static function armarInsertVentaDetalle(array $datos, stdClass $producto): array
  {
    $insert = [];
    $insert['venta_id'] = $datos['ventaId'];
    $insert['producto_id'] = $producto->producto_id;
    $insert['total'] = $producto->total;
    $insert['cantidad'] = $producto->cantidad;
    $insert['precio_unitario'] = $producto->precio;
    $insert['clave'] = $producto->clave;
    $insert['producto'] = $producto->nombre;
    $insert['codigo_barras'] = $producto->codigo_barras;
    $insert['registro_autor_id'] = Utils::getUserId();
    $insert['registro_fecha'] = $datos['fechaActual'];

    return $insert;
  }
}
