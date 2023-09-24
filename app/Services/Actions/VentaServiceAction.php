<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\Cliente;
use App\Repos\Actions\ProductoRepoAction;
use App\Repos\Actions\VentaRepoAction;
use App\Services\BO\VentaBO;
use App\Services\Data\ProductoServiceData;
use App\Utils;
use App\UtilsDB;
use Illuminate\Support\Facades\DB;
use Exception;

class VentaServiceAction
{
  /**
   * agregar
   *
   * @param  mixed $datos [clienteId, productos, totalVenta, numeroProductos, fechaActual]
   * @return bool
   */
  public static function agregar(array $datos): bool
  {
    try {
      DB::beginTransaction();
      
      $datos['folio'] = UtilsDB::obtenerFolioGlobalForUpdate(Constants::CAT_FOLIO_GLOBAL_VENTA);

      $validar = self::validarAgregarVenta($datos);
      if (!$validar) {
        return false;
      }

      $insert = VentaBO::armarInsertVenta($datos);
      $datos['ventaId'] = VentaRepoAction::agregar($insert);

      $productos = json_decode($datos['productos']);
      $detalles = [];
      foreach ($productos as $producto) {
        $insertDetalle = VentaBO::armarInsertVentaDetalle($datos, $producto);
        array_push($detalles, $insertDetalle);
        ProductoRepoAction::actualizarStock($datos, $producto);
      }
      VentaRepoAction::agregarDetalle($detalles);

      self::validarVentaCompletada($datos);

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  /**
   * validarAgregar
   *
   * @param  mixed $datos [productos]
   * @return bool
   */
  private  static function validarAgregarVenta(array $datos): bool
  {
    try {
      $productos = json_decode($datos['productos']);
      foreach ($productos as $producto) {
        $productoObj = ProductoServiceData::listarBasico(['productoId' => $producto->producto_id])[0];
        $existenciaActual = $productoObj->existencia - $producto->cantidad;
        if ($existenciaActual < 0) {
          return false;
        }
      }
      return true;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /**
   * validarAgregar
   *
   * @param  mixed $datos [productos]
   * @return bool
   */
  private  static function validarVentaCompletada(array $datos): bool
  {
    try {
      $productos = json_decode($datos['productos']);
      foreach ($productos as $producto) {
        $productoObj = ProductoServiceData::listarBasico(['productoId' => $producto->producto_id])[0];
        if ($productoObj->existencia < 0) {
          return false;
        }
      }
      return true;
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
