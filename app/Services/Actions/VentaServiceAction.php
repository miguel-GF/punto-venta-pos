<?php

namespace App\Services\Actions;

use App\Constants;
use App\Exceptions\ExceptionHandler;
use App\Printers\ESCPOS;
use App\Repos\Actions\ProductoRepoAction;
use App\Repos\Actions\VentaRepoAction;
use App\Services\BO\VentaBO;
use App\Services\Data\ProductoServiceData;
use App\Services\Data\VentaServiceData;
use App\UtilsDB;
use Mike42\Escpos\Printer;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
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

      $exitoTicket = self::imprimirVenta($datos['ventaId']);

      if (!$exitoTicket) {
        return 201;
      }

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
  public static function validarAgregarVenta(array $datos): bool
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
  private static function validarVentaCompletada(array $datos): bool
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
      // Obtenemos información de la venta
      $ventaObj = VentaServiceData::obtenerDetalle($id);

      // Obtenemos conexion de impresora
      $resConexion = ESCPOS::crearConexionImpresora();
      $printer = $resConexion->printer;

      // Seteamos encabezado de ticket
      ESCPOS::setearEncabezado($printer, $ventaObj->info->registro_fecha);
      
      $printer->setJustification(Printer::JUSTIFY_LEFT);
      $printer->setFont(Printer::FONT_B);
      $printer->text("PRODUCTOS\n");
      $printer->text("\n");
      // Definir el ancho de las columnas (ajusta según tus necesidades)
      $columnWidths = [24, 10, 15];

      // Definir los encabezados de las columnas
      $columnHeaders = ["DESC", "CANT", "TOTAL"];
      // Imprimir los encabezados de las columnas
      foreach ($columnHeaders as $key => $header) {
        $printer->text(str_pad($header, $columnWidths[$key]));
      }
      
      $printer->text("\n");
      // Imprimir los datos de la tabla
      foreach ($ventaObj->detalles as $rowData) {
        $productoNombre = $rowData->producto;
        if(strlen($rowData->producto) > 20) {
          $productoNombre = substr($productoNombre, 0, 20);
        }
        $valorCantidad = floatval($rowData->cantidad);
        $cantidad = number_format($valorCantidad, 2, '.', ',');
        $fmt = new NumberFormatter('es_MX', NumberFormatter::CURRENCY);
        $total = $fmt->formatCurrency($rowData->total, "MXN");
        $datos = [
          strtoupper($productoNombre),
          $cantidad,
          $total,
        ];
        foreach ($datos as $key => $data) {
          $printer->text(str_pad($data, $columnWidths[$key]));
        }
        $printer->text("\n");
      }

      ESCPOS::setearSeparador($printer);
      
      $cantidadTotal = number_format($ventaObj->info->cantidad, 2, '.', ',');
      $ventaTotal = $fmt->formatCurrency($ventaObj->info->total, "MXN");
      $articulos = "ARTICULOS: " . $cantidadTotal;
      $totales = "TOTAL: " . $ventaTotal;
      $columnWidths = [28, 24];
      $columnHeaders = [$articulos, $totales];
      foreach ($columnHeaders as $key => $header) {
        $printer->text(str_pad($header, $columnWidths[$key]));
      }
      $printer->text("\n");
      
      ESCPOS::setearLeyendaPie($printer);

      // Cortar el papel
      $printer->cut();

      // Cerrar la conexión con la impresora
      $printer->close();
      
      return true;
    } catch (Throwable $th) {
      ExceptionHandler::manejarException($th);
      return false;
    }
  }
}
