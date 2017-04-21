<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Http\Model\LevelModel;

class VoiceManagementMiddleware
{
    public function handle($request, Closure $next)
    {
        //取出用户权限
        $level_users=Session::get('user');
        $level_users=$level_users[0]['staff_level'];
        $level_users=explode(',',$level_users);

        $level_mysql=array_flatten(LevelModel::where(['level_name'=>'声纹管理'])->get(['level_id'])->toArray());
        $level_mysql=$level_mysql[0];

        if(in_array($level_mysql,$level_users))
        {
            return $next($request);
        }else
        {
            return redirect('/');
        }
    }
}
