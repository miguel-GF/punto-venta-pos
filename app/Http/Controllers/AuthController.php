<?php

namespace App\Http\Controllers;

use App\Services\Actions\AuthServiceAction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		$request->validate([
			'correo' => 'required|email',
			'password' => 'required',
		]);

		$datos = $request->all();

		$logeado = AuthServiceAction::login($datos);
		if ($logeado) {
			return Inertia::location(route('dashboard'));
		}

		return Inertia::render('Login', [
			'status' => 300,
			'error' => 'Usuario o contraseña incorrecto'
		]);
	}

	public function logout()
	{
		$session = Session();
		$session->flush();
		return redirect()->route('login');
	}
}
