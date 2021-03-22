<?php


namespace App\Http\Middleware;
use Closure;

class RoleAdmin
{
    public function handle($request, Closure $next)
    {
        if ($request->user()&&$request->user()->role=='1') {
            return $next($request);
        } else {
            return redirect('/no-access');
        }

    }
}
