<?php

namespace App\Services\Actions;

use App\Constants;
use App\Repos\Actions\ProductoRepoAction;
use App\Repos\Actions\VentaRepoAction;
use App\Services\BO\VentaBO;
use App\Services\Data\ProductoServiceData;
use App\Services\Data\VentaServiceData;
use App\UtilsDB;
use App\UtilsTicket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;
use Throwable;

class VentaServiceAction
{
  /**
   * agregar
   *
   * @param  mixed $datos [clienteId, productos, totalVenta, numeroProductos, fechaActual]
   * @return mixed
   */
  public static function agregar(array $datos): mixed
  {
    try {
      DB::beginTransaction();
      
      $datos['folio'] = UtilsDB::obtenerFolioGlobalForUpdate(Constants::CAT_FOLIO_GLOBAL_VENTA);

      $validar = self::validarAgregarVenta($datos);
      if (!$validar) {
        return 301;
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

      $validar = self::validarVentaCompletada($datos);
      if (!$validar) {
        return 302;
      }

      DB::commit();

      // $resTicket = self::imprimirVenta($datos['ventaId']);

      // return $resTicket->status;
      return 200;
    } catch (Throwable $th) {
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
    } catch (Throwable $th) {
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
    } catch (Throwable $th) {
      throw $th;
    }
  }

  public static function imprimirVenta($id)
  {
    try {
      $res = new stdClass();
      $res->status = 200;
      $res->trackError = null;

      // Obtenemos conexion de impresora
      $resConexion = UtilsTicket::crearConexionImpresora();
      $printer = $resConexion->printer;
      
      // Seteamos encabezado de ticket
      UtilsTicket::setearEncabezado($printer);

      // Obtenemos información de la venta
      $ventaObj = VentaServiceData::obtenerDetalle($id);

      // Definir los encabezados de las columnas
      $columnHeaders = ["Clave", "Descripción", "Cantidad", "Total"];

      // Definir el ancho de las columnas (ajusta según tus necesidades)
      $columnWidths = [25, 35, 20, 20];

      // Imprimir los encabezados de las columnas
      foreach ($columnHeaders as $key => $header) {
        $printer->text(str_pad($header, $columnWidths[$key]));
      }

      $printer->text("\n");

      // Imprimir los datos de la tabla
      foreach ($ventaObj->detalles as $rowData) {
        $datos = [
          $rowData->clave,
          $rowData->producto,
          $rowData->cantidad,
          $rowData->total,
        ];
        foreach ($datos as $key => $data) {
          $printer->text(str_pad($data, $columnWidths[$key]));
        }
        $printer->text("\n");
      }

      // Cortar el papel
      $printer->cut();

      // Cerrar la conexión con la impresora
      $printer->close();

      // copy($file, "//localhost/PDF24");
      // unlink($file);

      Log::info("Llego al final");
      return $res;
    } catch (Throwable $th) {
      
      $res->status = 303;
      $res->trackError = $th;
      return $res;
    }
  }
}
