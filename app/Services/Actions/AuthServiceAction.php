<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\Usuario;

class AuthServiceAction
{
  /**
   * loginDocente
   *
   * @param  mixed $datos [correo, password]
   * @return bool
   */
  public static function login(array $datos)
  {
    $user = Usuario::select('id', 'nombre', 'correo')
      ->where('correo', $datos['correo'])
      ->where('password', md5($datos['password']))
      ->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
      ->get()->first();

    if (!empty($user)) {
      $session = Session();
      $user->tipo = "sistema";
      $session->put('user', $user);
      return true;
    } else {
      return false;
    }
  }
}
