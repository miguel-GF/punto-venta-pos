<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\Usuario;
use App\Utils;
use Illuminate\Support\Facades\DB;

class UsuarioServiceAction
{
  /**
   * agregar
   *
   * @param  mixed $datos [nombre, correo, password]
   * @return bool
   */
  public static function agregar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      if (!empty($datos['correo'])) {
        $existe = Usuario::where('correo', $datos['correo'])->exists();
  
        if ($existe) {
          return false;
        }
      }

      // Crear un nuevo objeto de usuario
      $producto = new Usuario([
        'nombre' => $datos['nombre'],
        'correo' => $datos['correo'],
        'password' => md5($datos['password']),
        'mostrar' => 1,
        'registro_autor_id' => Utils::getUserId(),
        'registro_fecha' => $datos['fechaActual'],
      ]);

      $producto->save();

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }

  /**
   * editar
   *
   * @param  mixed $datos [usuarioId, nombre, correo, password, fechaActual]
   * @return bool
   */
  public static function editar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      if (!empty($datos['correo'])) {
        $existe = Usuario::where('correo', $datos['correo'])->where('usuario_id', '<>', $datos['usuarioId']) ->exists();
  
        if ($existe) {
          return false;
        }
      }

      // Buscar el usuario existente por su id
      $usuario = Usuario::find($datos['usuarioId']);

      // Actualizar los campos del usuario
      $usuario->nombre = $datos['nombre'];
      $usuario->correo = $datos['correo'];

      if ($datos['editarPassword'] == 'si') {
        $usuario->password = md5($datos['password']);
      }
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

  /**
   * editar
   *
   * @param  mixed $datos [usuarioId, fechaActual]
   * @return bool
   */
  public static function eliminar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      // Buscar el usuario existente por su id
      $usuario = Usuario::find($datos['usuarioId']);

      // Eliminamos el usuario
      $usuario->status = Constants::BAJA_STATUS;
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
