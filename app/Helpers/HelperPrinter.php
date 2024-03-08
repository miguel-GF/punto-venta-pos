<?php

namespace App\Helpers;

use App\Exceptions\PrinterException;
use Throwable;

class HelperPrinter
{  
  /**
   * obtenerListadoImpresoras
   *
   * @return array
   */
  public static function obtenerListadoImpresoras(): array
  {
    try {
      //code...
      // Ejecutar el comando wmic para obtener la lista de impresoras en Windows
      $output = shell_exec('wmic printer get Name');
  
      // Verificar si se obtuvo la lista de impresoras correctamente
      if ($output === null) {
        throw new PrinterException("No se pudo obtener la lista de impresoras.");
      } else {
        // Dividir la salida en líneas y eliminar la primera línea (encabezado)
        $lineas = explode("\n", trim($output));
        array_shift($lineas); // Eliminar la primera línea
        array_pop($lineas); // Eliminar la última línea (espacio en blanco)
  
        // Obtener los nombres de las impresoras
        $impresoras = [];
        foreach ($lineas as $linea) {
          $nombre_impresora = trim($linea);
          $impresoras[] = $nombre_impresora;
        }
        // Log::info(json_encode($impresoras));
        return $impresoras;
      }
    } catch (Throwable $th) {
      throw new PrinterException($th);
    }
  }
}
