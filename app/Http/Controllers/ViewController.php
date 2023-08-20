<?php

namespace App\Http\Controllers;

use App\Utils;
use Inertia\Inertia;

class ViewController extends Controller
{
    public function loginView()
    {
        return Inertia::render('Login', []);
    }

    public function docenteDashboardView()
    {
        $user = Utils::getUser();
        return Inertia::render('Dashboard', [
            'usuario' => $user,
        ]);
    }
}
