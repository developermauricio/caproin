<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ModuleProducts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()) {
            if (Auth::user()->hasRole('Administrador') ||
                Auth::user()->hasRole('Vendedor')||
                Auth::user()->hasRole('Asistente Sucursal')
            ) {
                return $next($request);
            } else {
                return back()->with('error', 'No tiene permisos para acceder a esta parte del sistema, consulte con el administrador');
            }
        }
    }
}
