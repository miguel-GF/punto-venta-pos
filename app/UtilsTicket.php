<?php

namespace App;

use App\Models\Usuario;
use App\Services\Data\SucursalServiceData;
use Exception;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use stdClass;

class UtilsTicket
{
  public static function obtenerImpresoraPredeterminada()
  {
    try {
      $existePredeterminada = false;
      $defaultPrinter = exec("wmic printer get name, default | findstr /i \"true\"");
      if ($defaultPrinter) {
        $impresoraPredeterminada = ltrim(str_replace('TRUE', '', $defaultPrinter));
        $existePredeterminada = true;
        Log::debug("Impresora predeterminada: $impresoraPredeterminada");
      }

      $res = new stdClass();
      $res->existePredeterminada = $existePredeterminada;
      $res->impresoraPredeterminada = $impresoraPredeterminada;

      return $res;
    } catch (\Throwable $th) {
      Log::error("Problema al obtener impresora predeterminada: $th");
      throw $th;
    }
  
  
  }

  public static function crearConexionImpresora()
  {
    try {
      // Buscamos la configuración del usuario respecto a la impresora
      $usuarioId = Utils::getUserId();
      $usuarioSesion = Usuario::find($usuarioId);
      $defaultPrinter = new stdClass();
      if ($usuarioSesion->impresora_predeterminada) {
        Log::info("casilla activada de impresora_predeterminada");
        $defaultPrinter = UtilsTicket::obtenerImpresoraPredeterminada();
        if (!$defaultPrinter->existePredeterminada) {
          throw new Exception("No hay impresora predeterminada configurada");
        }
      } else {
        Log::info("casilla desactivada de impresora_predeterminada");
        $defaultPrinter->existePredeterminada = false;
        $defaultPrinter->impresoraPredeterminada = $usuarioSesion->impresora_nombre;
      }
      
      //
      $profile = CapabilityProfile::load("simple");
      $connector = new WindowsPrintConnector($defaultPrinter->impresoraPredeterminada);
      $printer = new Printer($connector, $profile);

      $defaultPrinter->printer = $printer;

      return $defaultPrinter;
    } catch (\Throwable $th) {
      Log::error("Problema al conectarse con impresora de ticket: $th");
      throw $th;
    }
  }
  public static function setearEncabezado($printer)
  {
    try {
      $sucursalDefault = SucursalServiceData::listarBasico([
        'status' => Constants::ACTIVO_STATUS,
        'default' => 1
      ])[0];

      $printer->text("$sucursalDefault->nombre\n");
      $printer->text("$sucursalDefault->direccion\n");

      if ($sucursalDefault->rfc) {
        $printer->text("$sucursalDefault->rfc\n");
      }

      if ($sucursalDefault->telefono) {
        $printer->text("$sucursalDefault->telefono\n");
      }
    } catch (\Throwable $th) {
      Log::error("Problema al imprimir encabezado de ticket: $th");
      throw $th;
    }
  }
}
