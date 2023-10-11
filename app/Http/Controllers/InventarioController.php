<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\Actions\InventarioServiceAction;
use App\Services\Data\InventarioServiceData;
use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class InventarioController extends Controller
{
	public function agregarMovimientoInventarioView()
	{
		try {
			$user = Utils::getUser();
			$movimientos = InventarioServiceData::listar([]);

      $usuarioConfig = Usuario::find(Utils::getUserId());
      $user->lectura_modo_monitor = $usuarioConfig->lectura_modo_monitor;
			return Inertia::render('Inventarios/AgregarMovimientoInventario', [
				'usuario' => $user,
				'movimientos' => $movimientos,
			]);
		} catch (QueryException $qe) {
			Log::error($qe);
			throw $qe;
		} catch (Throwable $th) {
			Log::error($th);
			throw $th;
		}
	}
	public function agregar(Request $request)
	{
		$request->validate([
			'busqueda' => 'required',
			'tipoMovimiento' => 'required',
			'cantidad' => 'required',
			'fechaActual' => 'required',
		]);

		$datos = $request->all();

		$exito = InventarioServiceAction::agregar($datos);
		if ($exito == 200) {
			$status = 200;
			$mensaje = "Inventario actualizado correctamente";
		} elseif ($exito == 300) {
			$status = 300;
			$mensaje = "No se encontro el producto asociado al valor {$datos['busqueda']}";
		} else {
			$status = 301;
			$mensaje = "No existe suficiente stock para hacer la operación";
		}

		$user = Utils::getUser();
		$movimientos = InventarioServiceData::listar([]);

		return Inertia::render('Inventarios/AgregarMovimientoInventario', [
			'usuario' => $user,
			'movimientos' => $movimientos,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}
}
