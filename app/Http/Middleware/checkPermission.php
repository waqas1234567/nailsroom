<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class checkPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$id)
    {
        $check =DB::table('adminpermissions')->where([['admin_id',Auth::user()->id],['adminpermission_id',$id]])->count();
        if($check!=1){
            abort(403);
        }

        return $next($request);
    }
}
