<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class LoginMiddleware
{
    public function handle($request, Closure $next)
    {
        if(Session::has('user'))
        {
            return $next($request);
        }else
        {
            return redirect('/');
        }
    }
}
