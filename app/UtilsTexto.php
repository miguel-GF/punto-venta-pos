<?php

namespace App;

class UtilsTexto
{
  public static function quitarAcentos($cadena) {
    // Convertir la cadena a UTF-8 si no lo está
    $cadena = mb_convert_encoding($cadena, 'UTF-8');

    // Reemplazar caracteres acentuados
    $acentos = array(
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
        'ñ' => 'n', 'Ñ' => 'N'
    );

    // Eliminar los caracteres especiales
    $cadena = strtr($cadena, $acentos);

    return $cadena;
  }

  public static function dividirCadena($cadena, $longitud_maxima = 32) {
    // Dividir la cadena en líneas de máximo 32 caracteres sin cortar palabras
    $lineas = explode("\n", wordwrap($cadena, $longitud_maxima, "\n"));

    // Unir líneas en cadenas completas
    $cadenas = [];
    $cadena_actual = '';
    foreach ($lineas as $linea) {
        $nueva_cadena = $cadena_actual . $linea;
        if (strlen($nueva_cadena) <= $longitud_maxima) {
            $cadena_actual = $nueva_cadena;
        } else {
            $cadenas[] = $cadena_actual;
            $cadena_actual = $linea;
        }
    }
    // Asegurar que la última cadena se agregue
    if (!empty($cadena_actual)) {
        $cadenas[] = $cadena_actual;
    }

    return $cadenas;
  }
}
