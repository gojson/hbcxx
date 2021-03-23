<?php
/**
 * Created by PhpStorm.
 * User: chenjingquan
 * Date: 2020/3/2
 * Time: 2:55 PM
 */

namespace App\Http\Middleware;

use Closure;

class WxLoginAuth
{
    public function handle($request, Closure $next)
    {
        $token = $request->header('token');
        $sig = $request->header('sig');
        $dump = $request->header('dump');
        if ($token && $sig && checkSignature($token, $sig, $dump)) {
            $userInfo = \App\Models\User::where("token",$token)->first();
            if(!empty($userInfo)){
                $request->merge(['openid'=>$userInfo->openid,'uid'=>$userInfo->id]);
                return $next($request);
            }
        }
        header('HTTP/1.1 403 Forbidden');
        header('Content-Type:application/json');
        return webReturn(403,'未登录');
    }
}
