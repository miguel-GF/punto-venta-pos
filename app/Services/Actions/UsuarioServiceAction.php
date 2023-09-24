<?php

namespace App\Services\Actions;

use App\Constants;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Utils;
use Illuminate\Support\Facades\DB;
use Exception;

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
   * @param  mixed $datos [clienteId, clave, codigoBarras, nombre, descripcion, precio, existencia, fechaActual]
   * @return bool
   */
  public static function editar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      if (!empty($datos['correo'])) {
        $existe = Cliente::where('correo', $datos['correo'])->where('cliente_id', '<>', $datos['clienteId']) ->exists();
  
        if ($existe) {
          return false;
        }
      }

      // Buscar el cliente existente por su id
      $cliente = Cliente::find($datos['clienteId']);

      // Actualizar los campos del cliente
      $cliente->nombre_comercial = $datos['nombreComercial'];
      $cliente->telefono = $datos['telefono'] ?? null;
      $cliente->eslogan = $datos['eslogan'] ?? null;
      $cliente->correo = $datos['correo'] ?? null;
      $cliente->tipo_persona = $datos['tipoPersona'] ?? null;
      $cliente->razon_social = $datos['razonSocial'] ?? null;
      $cliente->rfc = $datos['rfc'] ?? null;
      $cliente->codigo_postal = $datos['codigoPostal'] ?? null;
      $cliente->domicilio_fiscal = $datos['domicilioFiscal'] ?? null;
      $cliente->correo_fiscal = $datos['correoFiscal'] ?? null;
      $cliente->actualizacion_autor_id = Utils::getUserId();
      $cliente->actualizacion_fecha = $datos['fechaActual'];

      $cliente->save();

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
   * @param  mixed $datos [clienteId, fechaActual]
   * @return bool
   */
  public static function eliminar(array $datos): bool
  {
    try {
      DB::beginTransaction();

      // Buscar el cliente existente por su id
      $cliente = Cliente::find($datos['clienteId']);

      // Eliminamos el cliente
      $cliente->status = Constants::BAJA_STATUS;
      $cliente->actualizacion_autor_id = Utils::getUserId();
      $cliente->actualizacion_fecha = $datos['fechaActual'];

      $cliente->save();

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();
      throw $th;
    }
  }
}
