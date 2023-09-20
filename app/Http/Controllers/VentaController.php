<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\VentaRequest;
use App\OrderConstants;
use App\Services\Data\ClienteServiceData;
use App\Services\Data\ProductoServiceData;
use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class VentaController extends Controller
{
	public function agregarVentaView()
	{
		try {
			$user = Utils::getUser();
			$productos = ProductoServiceData::listarBasico([
				'status' => Constants::ACTIVO_STATUS,
				'ordenar' => OrderConstants::NOMBRE_ASC,
			]);
			$clientes = ClienteServiceData::listarBasico([
				'status' => Constants::ACTIVO_STATUS,
				'ordenar' => OrderConstants::NOMBRE_ASC,
			]);

			return Inertia::render('Ventas/AgregarVenta', [
				'usuario' => $user,
				'productos' => $productos,
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

	public function agregar(VentaRequest $request)
	{
		try {
			$datos = $request->all();
			return response([
				'mensaje' => 'Venta agregada correctamente',
				'status' => 200
			]);
		} catch (Throwable $th) {
			Log::error($th);
			response([
				'mensaje' => 'Ocurrió un error al agregar una nueva venta',
				'status' => 300
			], 300);
		}
	}
}
