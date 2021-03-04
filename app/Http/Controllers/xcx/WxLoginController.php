<?php namespace App\Http\Controllers\xcx;
	/***************************************************
	 * Created by PhpStorm.
	 * Author: json -- chenyuanliang@zmeng123.com
	 * Date: 2021/2/28 Time: 1:20 下午
	 *
	 ****************************************************/
class WxLoginController extends \App\Http\Controllers\Controller{
    /**
     * 登录，获取openid+session_key
     * @param $_POST['appid']
     * @param $_POST['code']
     * @param $t 来源入口类型['q'=>'二维码','s'=>'分享链接','n'=>'微信平台二维码或搜索小程序进入']
     * */
    public function login(\Illuminate\Http\Request $req){
        try{
            $appId  = $req->input('appid', '', 'trim');
            $code   = $req->input('code',  '', 'trim');
            if( !$appId || !$code ) {
                throw new \Exception("appid or code not exist");
            }
            $appInfo = \app\Models\AppInfo::where("appid",'=',$appId)->find();
            if(!$appInfo->appsecret){
                throw new \Exception("appsecret not exist");
            }
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appId}&secret={$appInfo->appSecret}&js_code={$code}&grant_type=authorization_code";
            $res = file_get_contents($url);
            // 2. 解析并获取
            $resArr = json_decode($res, true);
            if(empty($resArr)){
                throw new \Exception("resArr error");
            }
            if(!$resArr['session_key']){
                throw new \Exception("session_key error");
            }
            if(!$resArr['openid']){
                throw new \Exception("openid error");
            }
            $openId     = $resArr['openid'];
            $user = new \app\Models\User;
            $user->appid = $appId;
            $user->openid = $resArr['openid'];
            $user->token = $user->genLoginToken($appId, $openId);
            $user->session_key = $resArr['session_key'];
            $user->save();
            $sig = generalSignature($user->token);
            $resData = [
                'token'     => $user->token,
                'sig'       => $sig,
                'tel'       => $user->tel,
                'headimgurl'=> $user->headimgurl,
                'nickname'  => $user->nickname,
            ];
            return webReturn(200,'ok',$resData);
        }catch (\Exception $e){
            return webReturn(403,$e->getMessage());
        }
    }
}
