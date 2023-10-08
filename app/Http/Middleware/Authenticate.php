<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){
         //prd($request->path());
        //prd($guard);
        if(Auth::viaRemember()){
            //echo 'viaRemember'; die;
        }       
        elseif (Auth::guard($guard)->guest()) {
           
            $referer = $request->path();
            //  echo $request->ajax(); die;
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('home.login');
            }
        }
		
        return $next($request);
    }
}
