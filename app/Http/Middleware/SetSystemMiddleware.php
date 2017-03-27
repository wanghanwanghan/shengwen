<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class SetSystemMiddleware
{
    public function handle($request, Closure $next)
    {
        if(true)
        {
            return $next($request);
        }else
        {
            return redirect('/');
        }
    }
}
