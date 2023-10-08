<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Helpers\CustomHelper;

class AllowedSocietyModule{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleName){

        $SADMIN_ROUTE_NAME = config('custom.SADMIN_ROUTE_NAME');

        if(!CustomHelper::isAllowedSocietyModule($moduleName)){
            if (Auth::check()) {
                return redirect(url($SADMIN_ROUTE_NAME));
            }
            return redirect(url('/'));
        }

        return $next($request);
    }
}
