<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleAndPlant2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
// Middleware checkroleandplant2
public function handle($request, Closure $next)
{
    if (Auth::check() && Auth::user()->Planta == 'Planta2' && (Auth::user()->hasRole('Administrador') || Auth::user()->hasRole('Gerente de Calidad') || Auth::user()->hasRole('Auditor'))) {
        return $next($request);
    }

    // Redirige a la vista de error si el usuario no cumple con los criterios
    return redirect('error');
}


}
