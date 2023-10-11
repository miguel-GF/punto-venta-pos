<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Usuario;
use App\Services\Data\SucursalServiceData;
use App\Services\Data\VentaServiceData;
use App\Utils;
use App\UtilsTicket;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use stdClass;
use Throwable;

class TicketController extends Controller
{
  public function imprimirVenta2($id)
  {
    try {
      // Obtener una lista de impresoras disponibles en Windows
      // $printerList = WindowsPrintConnector::getPrinters();

      // Verificar si hay impresoras disponibles
      if (empty($printerList)) {
        throw new \Exception("No se encontraron impresoras configuradas en el sistema.");
      }

      // Obtener el nombre de la primera impresora en la lista
      $printerName = $printerList[0];

      // Crear una instancia del objeto Printer con la conexión de Windows
      $connector = new WindowsPrintConnector($printerName);
      $printer = new Printer($connector);

      // Contenido del ticket (HTML, texto plano o cualquier otro formato que desees imprimir)
      $contenidoTicket = "Ticket de venta\nDetalles de la venta...\n";

      // Imprimir el contenido del ticket
      $printer->text($contenidoTicket);

      // Cortar el ticket (opcional)
      $printer->cut();

      // Cerrar la conexión con la impresora
      $printer->close();
    } catch (QueryException $qe) {
      Log::error("Error al obtener información del ticket -> " . $qe);
      throw $qe;
    } catch (Throwable $th) {
      Log::error("Error al imprimir ticket -> " . $th);
      throw $th;
    }
  }

  public function imprimirVenta3($id)
  {
    // Ejecutar el comando wmic para obtener la lista de impresoras en Windows
    $output = shell_exec('wmic printer list brief');

    // Verificar si se obtuvo la lista de impresoras correctamente
    if ($output === null) {
      return "No se pudo obtener la lista de impresoras.";
    } else {
      // Dividir la salida en líneas y eliminar la primera línea (encabezado)
      $lineas = explode("\n", trim($output));
      array_shift($lineas); // Eliminar la primera línea

      // Obtener los nombres de las impresoras
      $impresoras = [];
      foreach ($lineas as $linea) {
        $datos = explode("|", trim($linea));
        $impresoras[] = trim($datos[0]);
      }

      // Devolver la lista de impresoras como vista
      Log::info(json_encode($impresoras));

      // Crear una instancia del objeto Printer con la conexión de Windows
      // $tmpdir = sys_get_temp_dir();
      // $file =  tempnam($tmpdir, 'ctk');
      // $connector = new DummyPrintConnector();
      // $connector = new FilePrintConnector($file);

      // $printer->setJustification(Printer::JUSTIFY_CENTER);
      // $printer->setTextSize(4, 5);
      // $printer->text("Imprimiendo\n");
      // $printer->text("ticket\n");
      // $printer->text("desde\n");
      // $printer->text("Laravel\n");
      // $printer->setTextSize(1, 1);
      // $printer->text("https://parzibyte.me");
      // $printer->feed(5);
      
      // for ($i=0; $i < 1000; $i++) { 
      //   $printer->text("https://parzibyte.me\n");
      //   $printer->feed();
      // }
    }
  }

  public function imprimirVenta($id)
  {
    try {
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
    } catch (Throwable $th) {
      throw $th;
    }
  }

  public static function title(Printer $printer, $text)
      {
          $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
          $printer -> text("\n" . $text);
          $printer -> selectPrintMode(); // Reset
      }
}
