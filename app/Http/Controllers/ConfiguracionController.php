<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\Actions\ConfiguracionServiceAction;
use App\Utils;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConfiguracionController extends Controller
{
  public function configuracionUsuarioSesionView()
	{
		$user = Utils::getUser();
    $usuarioConfiguracion = Usuario::find(Utils::getUserId());
		return Inertia::render('Configuraciones/ConfiguracionEditarUsuario', [
			'usuario' => $user,
      'usuarioConfiguracion' => $usuarioConfiguracion,
		]);
	}

  public function editarConfiguracionUsuarioSesion(Request $request)
	{
		$request->validate([
			'lecturaCompleta' => 'required',
			'fechaActual' => 'required',
		]);

		$datos = $request->all();

		$exito = ConfiguracionServiceAction::editarConfiguracionUsuarioSesion($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Configuraciones de usuario editado correctamente";
		} else {
			$status = 300;
			$mensaje = "Ocurrio un error a editar las configuraciones de usuario";
		}

		$user = Utils::getUser();
    $usuarioConfiguracion = Usuario::find(Utils::getUserId());
		return Inertia::render('Configuraciones/ConfiguracionEditarUsuario', [
			'usuario' => $user,
      'usuarioConfiguracion' => $usuarioConfiguracion,
      'status' => $status,
      'mensaje' => $mensaje,
		]);
	}
}
