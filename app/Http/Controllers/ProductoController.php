<?php

namespace App\Http\Controllers;

use App\Constants;
use App\Models\Producto;
use App\Services\Actions\ProductoServiceAction;
use App\Utils;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class ProductoController extends Controller
{
	public function productosView()
	{
		try {
			$user = Utils::getUser();
			$productos = DB::table('productos')
				->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->get()
				->toArray();

			return Inertia::render('Productos/ListadoProductos', [
				'usuario' => $user,
				'productos' => $productos,
			]);
		} catch (QueryException $qe) {
			Log::error($qe);
			throw $qe;
		} catch (Throwable $th) {
			Log::error($th);
			throw $th;
		}
	}
	public function agregarProductoView()
	{
		$user = Utils::getUser();
		return Inertia::render('Productos/AgregarProducto', [
			'usuario' => $user,
		]);
	}

	public function editarProductoView($id)
	{
		$user = Utils::getUser();
		$producto = Producto::where('producto_id', $id)->first();
		return Inertia::render('Productos/EditarProducto', [
			'usuario' => $user,
			'producto' => $producto,
		]);
	}

	public function agregar(Request $request)
	{
		$request->validate([
			'clave' => 'required',
			'codigoBarras' => 'required',
			'nombre' => 'required',
			'descripcion' => 'required',
			'precio' => 'required|numeric',
			'existencia' => 'required|numeric',
			'fechaActual' => 'required',
		]);

		$datos = $request->all();

		$exito = ProductoServiceAction::agregar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Producto agregado correctamente";
		} else {
			$status = 300;
			$mensaje = "La clave del producto ya existe, favor de verificar";
		}

		$user = Utils::getUser();

		return Inertia::render('Productos/AgregarProducto', [
			'usuario' => $user,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}

	public function editar(Request $request, $id)
	{
		$request->validate([
			'clave' => 'required',
			'codigoBarras' => 'required',
			'nombre' => 'required',
			'descripcion' => 'required',
			'precio' => 'required|numeric',
			'existencia' => 'required|numeric',
			'fechaActual' => 'required',
		]);

		$datos = $request->all();
		$datos['productoId'] = $id;

		$exito = ProductoServiceAction::editar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Producto editado correctamente";
		} else {
			$status = 300;
			$mensaje = "La clave del producto ya existe, favor de verificar";
		}

		$user = Utils::getUser();

		$producto = Producto::where('producto_id', $id)->first();

		return Inertia::render('Productos/EditarProducto', [
			'usuario' => $user,
			'status' => $status,
			'mensaje' => $mensaje,
			'producto' => $producto,
		]);
	}

	public function eliminar(Request $request, $id)
	{
		$request->validate([
			'fechaActual' => 'required',
		]);

		$datos = $request->all();
		$datos['productoId'] = $id;

		$exito = ProductoServiceAction::eliminar($datos);
		if ($exito) {
			$status = 200;
			$mensaje = "Producto eliminado correctamente";
		} else {
			$status = 300;
			$mensaje = "Ocurrio un error al intentar eliminar el producto";
		}

		$user = Utils::getUser();
		$productos = DB::table('productos')
				->whereRaw("LOWER(status) = ?", [strtolower(Constants::ACTIVO_STATUS)])
				->get()
				->toArray();

		return Inertia::render('Productos/ListadoProductos', [
			'usuario' => $user,
			'productos' => $productos,
			'status' => $status,
			'mensaje' => $mensaje,
		]);
	}
}
