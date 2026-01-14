<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictLocalAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Si no hay usuario autenticado o no tiene administrador asociado, denegar
        if (!$user || !$user->administrador) {
            abort(403, 'Acceso no autorizado.');
        }

        $admin = $user->administrador;

        // Si es Root, permitir acceso total
        if ($admin->tipo_admin === 'Root') {
            return $next($request);
        }

        // Si es Admin Local, bloquear acceso a rutas restringidas
        $restrictedRoutes = [
            'administradores.*',
            'configuracion.*',
        ];

        $currentRoute = $request->route()->getName();

        foreach ($restrictedRoutes as $pattern) {
            if (fnmatch($pattern, $currentRoute)) {
                abort(403, 'Solo los administradores Root tienen acceso a esta sección.');
            }
        }

        return $next($request);
    }
}
