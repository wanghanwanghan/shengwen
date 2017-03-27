<?php

namespace App\Http\Middleware;

use Closure;

class APIMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
