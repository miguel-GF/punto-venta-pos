<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Exceptions\ExceptionHandler;
use App\Http\Requests\VentaRequest;
use App\Models\Usuario;
use App\OrderConstants;
use App\Services\Actions\VentaServiceAction;
use App\Services\Data\ClienteServiceData;
use App\Services\Data\ProductoServiceData;
use App\Services\Data\VentaServiceData;
use App\Utils;
use Illuminate\Support\Facades\DB;
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
      $usuarioConfig = Usuario::find(Utils::getUserId());
      $user->lectura_modo_monitor = $usuarioConfig->lectura_modo_monitor;

			return Inertia::render('Ventas/AgregarVenta', [
				'usuario' => $user,
				'productos' => $productos,
				'clientes' => $clientes,
			]);
		} catch (Throwable $th) {
			ExceptionHandler::manejarException($th);
			throw $th;
		}
	}

	public function agregar(VentaRequest $request)
	{
		try {
			$datos = $request->all();
      $res = VentaServiceAction::agregar($datos);

      switch ($res) {
        case 301:
        case 302:
          $mensaje = 'No cuenta con el stock suficiente para completar la venta';
          $status = $res;
          break;

        case 201:
          $mensaje = 'Venta agregada correctamente pero no se pudo imprimir el ticket de la venta';
          $status = 201;
          break;
        
        default:
          $mensaje = 'Venta agregada correctamente';
          $status = 200;
          break;
      }
      return response([
        'mensaje' => $mensaje,
        'status' => $status
      ]);
		} catch (Throwable $th) {
			Log::error($th);
			response([
				'mensaje' => 'Ocurrió un error al agregar una nueva venta',
				'status' => 300
			], 300);
		}
	}

  public function imprimirTicket($ventaId)
	{
		try {
      $exito = VentaServiceAction::imprimirVenta($ventaId);
      if ($exito) {
        return response([
          'mensaje' => "Ticket de venta impreso correctamente",
          'status' => 200
        ]);
      }
			if ($exito) {
        return response([
          'mensaje' => "No se pudo imprimir ticket de venta",
          'status' => 300
        ]);
      }
		} catch (Throwable $th) {
			ExceptionHandler::manejarException($th);
			response([
				'mensaje' => 'Ocurrió un error al imprimir ticket de venta',
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
		} catch (Throwable $th) {
			ExceptionHandler::manejarException($th);
			throw $th;
		}
	}

  public function ventaDetalleView($id)
	{
		try {
			$user = Utils::getUser();

      $detalle = VentaServiceData::obtenerDetalle($id);

			return Inertia::render('Ventas/DetalleVenta', [
				'usuario' => $user,
				'venta' => $detalle->info,
				'ventaDetalle' => $detalle->detalles,
			]);

		} catch (Throwable $th) {
			ExceptionHandler::manejarException($th);
			throw $th;
		}
	}
}
