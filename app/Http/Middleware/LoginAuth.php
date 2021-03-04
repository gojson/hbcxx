<?php
/**
 * Created by PhpStorm.
 * User: chenjingquan
 * Date: 2020/3/2
 * Time: 2:55 PM
 */

namespace App\Http\Middleware;

use Closure;

class LoginAuth
{
    public function handle($request, Closure $next)
    {
        $loginRegId = session('reg_id');
        $regInfo = \App\Models\Register::where('id','=',$loginRegId)->first();
        if(!$regInfo || $regInfo->stat !== \App\Models\Register::STAT_SUC){
            return webReturn(403,'无权限');
        }
        return $next($request);
    }
}
