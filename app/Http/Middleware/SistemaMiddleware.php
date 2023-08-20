<?php

namespace App\Http\Middleware;

use App\Utils;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SistemaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Utils::getUser();
        if (!is_null($user)) {
            if ($user->tipo == "sistema") {
                return $next($request);
            }
        }

        return Inertia::location(route('login'));
    }
}
