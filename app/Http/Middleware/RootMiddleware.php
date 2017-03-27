<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class RootMiddleware
{
    public function handle($request, Closure $next)
    {
        if(Session::has('user'))
        {
            foreach (Session::get('user') as $row)
            {
                if ($row['staff_account']=='test001')
                {
                    return $next($request);
                }else
                {
                    return redirect('/');
                }
            }
        }else
        {
            return redirect('/');
        }
    }
}
