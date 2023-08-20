<?php

namespace App\Http\Controllers;

use App\Services\Actions\ProductoServiceAction;
use App\Services\Data\DocenteServiceData;
use App\Utils;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
	public function agregarProductoView()
	{
		$user = Utils::getUser();
		return Inertia::render('Productos/AgregarProducto', [
			'usuario' => $user,
		]);
	}

	/**
	 * docentePasarAsistenciaCargasAcademicasView
	 *
	 * @param  mixed $request
	 */
	public function docentePasarAsistenciasCargasAcademicasView($claveMateria)
	{
		$user = Utils::getUser();
		$cargasAcademicasAlumnos = DocenteServiceData::obtenerAlumnosPorCargaAcademica([
			'idProf' => $user->idusuarios,
			'claveMateria' => $claveMateria
		]);
		return Inertia::render('Docentes/DocentePasarAsistencia', [
			'alumnos' => $cargasAcademicasAlumnos,
			'usuario' => $user,
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
}
