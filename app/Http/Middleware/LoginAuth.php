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
        $regId = $request->header('reg_id',0);
        $regInfo = \App\Models\Register::where('id','=',$regId)->first();
        if(!$regInfo || $regInfo->stat !== \App\Models\Register::STAT_SUC){
            header('HTTP/1.1 403 Forbidden');
            header('Content-Type:application/json');
            return webReturn(403,'无权限');
        }
        $request->merge(['reg_id'=>$regInfo->id,'isAdmin'=>$regInfo->admin==1?true:false]);
        return $next($request);
    }
}
