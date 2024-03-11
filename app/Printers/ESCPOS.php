<?php

namespace App\Printers;

use App\Constants;
use App\Exceptions\PrinterException;
use App\Models\Usuario;
use App\Services\Data\SucursalServiceData;
use App\Utils;
use App\UtilsTexto;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use stdClass;
use Throwable;
use Illuminate\Support\Facades\Log;

class ESCPOS
{
  public static function crearConexionImpresora()
  {
    try {
      // Buscamos la configuración del usuario respecto a la impresora
      $usuarioId = Utils::getUserId();
      $usuarioSesion = Usuario::find($usuarioId);
      $defaultPrinter = new stdClass();

      if ($usuarioSesion->impresora_predeterminada) {
        $defaultPrinter = self::obtenerImpresoraPredeterminada();
        if (!$defaultPrinter->existePredeterminada) {
          throw new PrinterException("No hay impresora predeterminada configurada");
        }
      } else {
        if (!$usuarioSesion->impresora_nombre) {
          throw new PrinterException("No hay impresora configurada por el usuario");
        }
        $defaultPrinter->existePredeterminada = false;
        $defaultPrinter->impresoraPredeterminada = $usuarioSesion->impresora_nombre;
      }

      $profile = CapabilityProfile::load("simple");
      $connector = new WindowsPrintConnector($defaultPrinter->impresoraPredeterminada);
      $printer = new Printer($connector, $profile);

      $defaultPrinter->printer = $printer;

      return $defaultPrinter;
    } catch (Throwable $th) {
      Log::error("Problema al conectarse con impresora de ticket: $th");
      throw new PrinterException($th);
    }
  }
  private static function obtenerImpresoraPredeterminada()
  {
    try {
      $existePredeterminada = false;
      $defaultPrinter = exec("wmic printer get name, default | findstr /i \"true\"");
      if ($defaultPrinter) {
        $impresoraPredeterminada = ltrim(str_replace('TRUE', '', $defaultPrinter));
        $existePredeterminada = true;
      }

      $res = new stdClass();
      $res->existePredeterminada = $existePredeterminada;
      $res->impresoraPredeterminada = $impresoraPredeterminada;

      return $res;
    } catch (Throwable $th) {
      Log::error("Problema al obtener impresora predeterminada: $th");
      throw new PrinterException($th);
    }
  }

  public static function setearEncabezado($printer, $fecha = null)
  {
    try {
      $sucursalDefault = SucursalServiceData::listarBasico([
        'status' => Constants::ACTIVO_STATUS,
        'default' => 1
      ])[0];

      $printer->setJustification(Printer::JUSTIFY_CENTER);
      $printer->text(strtoupper($sucursalDefault->nombre) ."\n");

      $direccion = strtoupper(UtilsTexto::quitarAcentos($sucursalDefault->direccion));
      if ($direccion > 32) {
        $dividida = UtilsTexto::dividirCadena($direccion, 32);
        $printer->text(implode("\n", $dividida));
      } else {
        $printer->text($direccion);
      }
      $printer->text("\n");

      if ($sucursalDefault->rfc) {
        $printer->text("RFC: $sucursalDefault->rfc\n");
      }

      if ($sucursalDefault->telefono) {
        $printer->text("TEL: $sucursalDefault->telefono\n");
      }
      if (!is_null($fecha)) {
        $printer->text("FECHA: $fecha\n");
      }
      $printer->text("\n");
      $printer->text("\n");
    } catch (Throwable $th) {
      Log::error("Problema al imprimir encabezado de ticket: $th");
      throw $th;
    }
  }

  public static function setearLeyendaPie($printer)
  {
    try {
      $sucursalDefault = SucursalServiceData::listarBasico([
        'status' => Constants::ACTIVO_STATUS,
        'default' => 1
      ])[0];

      if (!empty($sucursalDefault->ticket_leyenda_pie)) {
        $printer->text("\n");
        $printer->setJustification(Printer::JUSTIFY_CENTER);

        $leyendaPie = strtoupper(UtilsTexto::quitarAcentos($sucursalDefault->ticket_leyenda_pie));
        if (strlen($leyendaPie) > 32) {
          $dividida = UtilsTexto::dividirCadena($leyendaPie, 32);
          $printer->text(implode("\n", $dividida));
        } else {
          $printer->text($leyendaPie);
        }
        $printer->text("\n");
      }
    } catch (Throwable $th) {
      Log::error("Problema al imprimir encabezado de ticket: $th");
      throw $th;
    }
  }

  public static function setearSeparador($printer)
  {
    $printer->text(str_pad("-", 48, "-"));
    $printer->text("\n");
  }
}
