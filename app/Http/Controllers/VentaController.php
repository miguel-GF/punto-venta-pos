<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Http\Requests\VentaRequest;
use App\OrderConstants;
use App\Services\Actions\VentaServiceAction;
use App\Services\Data\ClienteServiceData;
use App\Services\Data\ProductoServiceData;
use App\Utils;
use Illuminate\Support\Facades\DB;
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
      $res = VentaServiceAction::agregar($datos);
      if (!$res) {
        return response([
          'mensaje' => 'No cuenta con el stock suficiente para completar la venta',
          'status' => 300
        ]);  
      }
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

  public function ventasView()
	{
		try {
			$user = Utils::getUser();
			$ventas = DB::table('ventas as  v')
        ->select(
          'v.*',
          'u.nombre as usuario_nombre'
        )
        ->join('usuarios as u', 'u.usuario_id', 'v.registro_autor_id')
				->whereRaw("LOWER(v.status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->orderByDesc("v.folio")
				->get()
				->toArray();

			return Inertia::render('Ventas/ListadoVentas', [
				'usuario' => $user,
				'ventas' => $ventas,
			]);
		} catch (QueryException $qe) {
			Log::error($qe);
			throw $qe;
		} catch (Throwable $th) {
			Log::error($th);
			throw $th;
		}
	}

  public function ventaDetalleView($id)
	{
		try {
			$user = Utils::getUser();
			$venta = DB::table('ventas as  v')
        ->select(
          'v.*',
          'u.nombre as usuario_nombre'
        )
        ->join('usuarios as u', 'u.usuario_id', 'v.registro_autor_id')
				->where("v.venta_id", "$id")
				->get()
				->first();

      $ventaDetalle = DB::table('ventas_detalle as vd')
				->where("vd.venta_id", "$id")
				->get()
				->toArray();

			return Inertia::render('Ventas/DetalleVenta', [
				'usuario' => $user,
				'venta' => $venta,
				'ventaDetalle' => $ventaDetalle,
			]);

		} catch (QueryException $qe) {
			Log::error($qe);
			throw $qe;
		} catch (Throwable $th) {
			Log::error($th);
			throw $th;
		}
	}
}
