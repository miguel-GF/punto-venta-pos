<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Cliente;
use App\Services\Actions\ClienteServiceAction;
use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class ClienteController extends Controller
{
	public function clientesView()
	{
		try {
			$user = Utils::getUser();
			$clientes = DB::table('clientes')
				->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->where('publico_general', false)
				->get()
				->toArray();

			return Inertia::render('Clientes/ListadoClientes', [
				'usuario' => $user,
				'clientes' => $clientes,
			]);
		} catch (QueryException $qe) {
			Log::error($qe);
			throw $qe;
		} catch (Throwable $th) {
			Log::error($th);
			throw $th;
		}
	}
	public function agregarClienteView()
	{
		$user = Utils::getUser();
		return Inertia::render('Clientes/AgregarCliente', [
			'usuario' => $user,
		]);
	}

	public function editarClienteView($id)
	{
		$user = Utils::getUser();
		$cliente = Cliente::where('cliente_id', $id)->first();
		return Inertia::render('Clientes/EditarCliente', [
			'usuario' => $user,
			'cliente' => $cliente,
		]);
	}

	public function agregar(Request $request)
	{
		$request->validate([
			'nombreComercial' => 'required',
		]);

		$datos = $request->all();

		$exito = ClienteServiceAction::agregar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Cliente agregado correctamente";
		} else {
			$status = 300;
			$mensaje = "El correo del cliente ya existe, favor de verificar";
		}

		$user = Utils::getUser();

		return Inertia::render('Clientes/AgregarCliente', [
			'usuario' => $user,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}

	public function editar(Request $request, $id)
	{
		$request->validate([
			'nombreComercial' => 'required',
		]);

		$datos = $request->all();
		$datos['clienteId'] = $id;

		$exito = ClienteServiceAction::editar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Cliente editado correctamente";
		} else {
			$status = 300;
			$mensaje = "La correo del cliente ya existe, favor de verificar";
		}

		$user = Utils::getUser();

		$cliente = Cliente::where('cliente_id', $id)->first();

		return Inertia::render('Clientes/EditarCliente', [
			'usuario' => $user,
			'status' => $status,
			'mensaje' => $mensaje,
			'cliente' => $cliente,
		]);
	}

	public function eliminar(Request $request, $id)
	{
		$request->validate([
			'fechaActual' => 'required',
		]);

		$datos = $request->all();
		$datos['clienteId'] = $id;

		$exito = ClienteServiceAction::eliminar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Cliente eliminado correctamente";
		} else {
			$status = 300;
			$mensaje = "Ocurrio un error al intentar eliminar el cliente";
		}

		$user = Utils::getUser();
		$clientes = DB::table('clientes')
				->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->where('publico_general', false)
				->get()
				->toArray();

		return Inertia::render('Clientes/ListadoClientes', [
			'usuario' => $user,
			'clientes' => $clientes,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}
}
