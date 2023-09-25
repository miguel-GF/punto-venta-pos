<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Usuario;
use App\Services\Actions\UsuarioServiceAction;
use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class UsuarioController extends Controller
{
	public function usuariosView()
	{
		try {
			$user = Utils::getUser();
			$usuarios = DB::table('usuarios')
				->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->where('mostrar', 1)
				->get()
				->toArray();

			return Inertia::render('Usuarios/ListadoUsuarios', [
				'usuario' => $user,
				'usuarios' => $usuarios,
			]);
		} catch (QueryException $qe) {
			Log::error($qe);
			throw $qe;
		} catch (Throwable $th) {
			Log::error($th);
			throw $th;
		}
	}
	public function agregarUsuarioView()
	{
		$user = Utils::getUser();
		return Inertia::render('Usuarios/AgregarUsuario', [
			'usuario' => $user,
		]);
	}

	public function agregar(Request $request)
	{
		$request->validate([
			'nombre' => 'required',
			'correo' => 'required|email|unique:usuarios,correo',
			'password' => 'required',
		]);

		$datos = $request->all();

		$exito = UsuarioServiceAction::agregar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Usuario agregado correctamente";
		} else {
			$status = 300;
			$mensaje = "El correo del usuario ya existe, favor de verificar";
		}

		$user = Utils::getUser();

		return Inertia::render('Usuarios/AgregarUsuario', [
			'usuario' => $user,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}

  public function editarUsuarioView($id)
	{
		$user = Utils::getUser();
		$usuario = Usuario::where('usuario_id', $id)->first();
		return Inertia::render('Usuarios/EditarUsuario', [
			'usuario' => $user,
			'usuarioEditar' => $usuario,
		]);
	}

	public function editar(Request $request, $id)
	{
		$request->validate([
			'nombre' => 'required',
			'correo' => 'required',
			'editarPassword' => 'required',
			'password' => 'nullable',
		]);

		$datos = $request->all();
		$datos['usuarioId'] = $id;

		$exito = UsuarioServiceAction::editar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Usuario editado correctamente";
		} else {
			$status = 300;
			$mensaje = "La correo del usuario ya existe, favor de verificar";
		}

		$user = Utils::getUser();

		$usuario = Usuario::where('usuario_id', $id)->first();

		return Inertia::render('Usuarios/EditarUsuario', [
			'usuario' => $user,
			'status' => $status,
			'mensaje' => $mensaje,
			'usuarioEditar' => $usuario,
		]);
	}

	public function eliminar(Request $request, $id)
	{
		$request->validate([
			'fechaActual' => 'required',
		]);

		$datos = $request->all();
		$datos['usuarioId'] = $id;

		$exito = UsuarioServiceAction::eliminar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Usuario eliminado correctamente";
		} else {
			$status = 300;
			$mensaje = "Ocurrio un error al intentar eliminar el usuario";
		}

		$user = Utils::getUser();
		$usuarios = DB::table('usuarios')
				->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->where('mostrar', 1)
				->get()
				->toArray();

		return Inertia::render('Usuarios/ListadoUsuarios', [
			'usuario' => $user,
			'usuarios' => $usuarios,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}
}
