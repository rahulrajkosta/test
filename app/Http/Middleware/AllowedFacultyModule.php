<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\Helpers\CustomHelper;
use App\Permission;

class AllowedFacultyModule{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $moduleName,$key){

        $FACULTY_ROUTE_NAME = config('custom.FACULTY_ROUTE_NAME');

        if(!CustomHelper::isFacultyAllowedModule($moduleName)){

            if (Auth::check()) {
                return redirect(url($FACULTY_ROUTE_NAME));
            }
            return redirect(url('/faculty'));
        }

       
        else{
             return $next($request);
        }

        
       
    }



//       public function handle($request, Closure $next, $moduleName){

//         $ADMIN_ROUTE_NAME = config('custom.ADMIN_ROUTE_NAME');
//         if(!CustomHelper::isAllowedModule($moduleName)){



//             if(Auth::guard('admin')->user()->role_id == 0){
//                if (Auth::check()) {
//                 return redirect(url($ADMIN_ROUTE_NAME));
//             }
//         }else{
//             $exist = Permission::where('section',$moduleName)->first();
//             if(!empty($exist)){
//                 if($exist->list == 1){
//                  if (Auth::check()) {
//                     return redirect(url($ADMIN_ROUTE_NAME));
//                 }
//             }else{
//                 return redirect(url('/admin'));

//             }
//         }

//     }

//     return redirect(url('/admin'));
// }

// return $next($request);
// }



}
