<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthSocietyAdmin{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = "sadmin"){

        $SADMIN_ROUTE_NAME = config('custom.SADMIN_ROUTE_NAME');
       // prd(Auth::guard($guard)->check());
        if (!Auth::guard('sadmin')->check()) {
            return redirect($SADMIN_ROUTE_NAME.'/login');
        }
        return $next($request);
    }
}
