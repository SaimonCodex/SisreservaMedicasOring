<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateDoubleMD5
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('password')) {
            $password = $request->input('password');
            if (!preg_match('/^[a-f0-9]{32}$/', $password)) {
                $request->merge(['password' => md5(md5($password))]);
            }
        }
        
        if ($request->has('respuesta')) {
            $respuesta = $request->input('respuesta');
            if (!preg_match('/^[a-f0-9]{32}$/', $respuesta)) {
                $request->merge(['respuesta' => md5(md5($respuesta))]);
            }
        }

        return $next($request);
    }
}