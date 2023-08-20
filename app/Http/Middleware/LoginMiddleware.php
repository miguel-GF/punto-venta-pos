<?php

namespace App\Http\Middleware;

use App\Utils;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $user = Utils::getUser();
        if (Session::has("user")) { 
            if ($user->tipo == "sistema") {
                return Inertia::location(route("dashboard"));
            }
        }

        return $next($request);
    }
}
