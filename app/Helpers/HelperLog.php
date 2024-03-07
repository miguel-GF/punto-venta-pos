<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;

class HelperLog
{
  public static function log(Exception $th, $nivel)
  {
    $codigo = $th->getCode();
    $archivo = $th->getFile();
    $linea = $th->getLine();
    $mensaje = $th->getMessage();
    // $trace = $th->getTrace();

    Log::{$nivel}("Codigo -> " . $codigo);
    Log::{$nivel}("Archivo -> " . $archivo);
    Log::{$nivel}("Linea -> " . $linea);
    Log::{$nivel}("Mensaje -> " . $mensaje);
    // Log::{$nivel}("Trace -> " . $trace);
  }
}
