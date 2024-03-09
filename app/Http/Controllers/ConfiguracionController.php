<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Exceptions\ExceptionHandler;
use App\Helpers\HelperPrinter;
use App\Models\Usuario;
use App\Services\Actions\ConfiguracionServiceAction;
use App\Services\Data\SucursalServiceData;
use App\Utils;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Throwable;

class ConfiguracionController extends Controller
{
  public function configuracionUsuarioSesionView()
	{
		$user = Utils::getUser();
    $usuarioConfiguracion = Usuario::find(Utils::getUserId());
    // $impresoras = HelperPrinter::obtenerListadoImpresoras();
		return Inertia::render('Configuraciones/ConfiguracionEditarUsuario', [
			'usuario' => $user,
      'usuarioConfiguracion' => $usuarioConfiguracion,
      'impresoras' => [],
		]);
	}

  public function editarConfiguracionUsuarioSesion(Request $request)
	{
		$request->validate([
			'lecturaCompleta' => 'required',
			'fechaActual' => 'required',
			'impresoraPredeterminada' => 'required',
			'impresoraNombre' => 'nullable',
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
    $impresoras = HelperPrinter::obtenerListadoImpresoras();
		return Inertia::render('Configuraciones/ConfiguracionEditarUsuario', [
			'usuario' => $user,
      'usuarioConfiguracion' => $usuarioConfiguracion,
      'impresoras' => $impresoras,
      'status' => $status,
      'mensaje' => $mensaje,
		]);
	}

  public function configuracionSucursalDefaultView()
	{
		$user = Utils::getUser();
    $sucursalDefault = SucursalServiceData::listarBasico([
      'status' => Constants::ACTIVO_STATUS,
      'default' => 1
    ])[0];
		return Inertia::render('Configuraciones/ConfiguracionEditarSucursalDefault', [
			'usuario' => $user,
      'sucursalDefault' => $sucursalDefault,
		]);
	}

  public function editarConfiguracionSucursalDefault(Request $request)
	{
    try {
      $request->validate([
        'sucursalId' => 'required',
        'nombre' => 'required',
        'direccion' => 'required',
        'fechaActual' => 'required',
        'telefono' => 'nullable',
        'rfc' => 'nullable',
        'ticketLeyendaPie' => 'nullable',
      ]);
  
      $datos = $request->all();
  
      $exito = ConfiguracionServiceAction::editarConfiguracionSucursalDefault($datos);
      if ($exito) {
        $status = 200;
        $mensaje = "Datos de sucursal editada correctamente";
      } else {
        $status = 300;
        $mensaje = "Ocurrio un error al editar datos de sucursal";
      }
  
      $user = Utils::getUser();
      $sucursalDefault = SucursalServiceData::listarBasico([
        'status' => Constants::ACTIVO_STATUS,
        'default' => 1
      ])[0];
      return Inertia::render('Configuraciones/ConfiguracionEditarSucursalDefault', [
        'usuario' => $user,
        'sucursalDefault' => $sucursalDefault,
        'status' => $status,
        'mensaje' => $mensaje,
      ]);
    } catch (Throwable $th) {
      ExceptionHandler::manejarException($th);
      $user = Utils::getUser();
      $sucursalDefault = SucursalServiceData::listarBasico([
        'status' => Constants::ACTIVO_STATUS,
        'default' => 1
      ])[0];
      return Inertia::render('Configuraciones/ConfiguracionEditarSucursalDefault', [
        'usuario' => $user,
        'sucursalDefault' => $sucursalDefault,
        'status' => 300,
        'mensaje' => "Ocurrio un error al editar datos de sucursal",
      ]);
    }
	}
}
