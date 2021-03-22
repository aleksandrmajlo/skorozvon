<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class RoleOperator
{
    public function handle($request, Closure $next)
    {
        if ($request->user() && $request->user()->role == '2' && $request->user()->status == 1) {
//            $ip = $request->ip();
//            $setting = DB::table('settings')->where('id',2)->first('value');
//            $ips=explode(',',trim($setting->value));
//            if($ips&&in_array($ip,$ips)){
                return $next($request);
//            }else{
//                return redirect('/no-access');
//            }
        } else {
            return redirect('/no-access');
        }
    }
}
