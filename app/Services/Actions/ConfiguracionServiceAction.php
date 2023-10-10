<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\Usuario;
use App\Utils;
use Illuminate\Support\Facades\DB;

class ConfiguracionServiceAction
{
  /**
   * editar
   *
   * @param  mixed $datos [lecturaCompleta, fechaActual]
   * @return bool
   */
  public static function editarConfiguracionUsuarioSesion(array $datos): bool
  {
    try {
      DB::beginTransaction();

      // Buscar el usuario existente por su id
      $usuario = Usuario::find(Utils::getUserId());

      $usuario->lectura_modo_monitor = $datos['lecturaCompleta'] == 'si' ? true: false;
      $usuario->actualizacion_autor_id = Utils::getUserId();
      $usuario->actualizacion_fecha = $datos['fechaActual'];

      $usuario->save();

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }
}
