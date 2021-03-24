<?php namespace App\Http\Controllers\xcx;
	use Illuminate\Http\Request;

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
            $appInfo = \App\Models\AppInfo::where("appid",'=',$appId)->first();
            if(!$appInfo->appsecret){
                throw new \Exception("appsecret not exist");
            }
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$appId}&secret={$appInfo->appsecret}&js_code={$code}&grant_type=authorization_code";
            $res = file_get_contents($url);
            // 2. 解析并获
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
            $userModel = new \App\Models\User();
            $user = \App\Models\User::where('openid','=',$openId)->first();
            $token = $userModel->genLoginToken($appId, $openId);
            if($user){//更新
                $suc = $user->where('openid','=',$openId)->update([
                    'appid' => $appId,
                    'openid'=> $openId,
                    'token' => $token,
                    'session_key' => $resArr['session_key'],
                    'updated_at'    => date("Y-m-d H:i:s"),
                ]);
            }else{
                $userModel = new \App\Models\User();
                $userModel->appid = $appId;
                $userModel->openid = $openId;
                $userModel->token = $token;
                $userModel->updated_at = date("Y-m-d H:i:s");
                $userModel->save();
            }
            if(!$suc){
                throw new \Exception("update error");
            }
            $sig = generalSignature($token);
            $user = \App\Models\User::where('openid','=',$openId)->first();
            $resData = [
                'token'     => $token,
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


    /**
     * 检查token是否可用
     * author: chenyuanliang
     * $param
     * @return
     */
    public function tokenAvaliable(Request $req){
        try{
            $token = $req->header("token","");
            if($token){
                $userInfo = \App\Models\User::where("token",'=',$token)->first();
                if(empty($userInfo)){
                    throw new \Exception("token已过期");
                }
                if((time()-strtotime($userInfo['updated_at'])<3600)){
                    return webReturn(200,"token已过期,请重新登录",['login'=>false]);
                }
                return webReturn(200,"ok",['login'=>true]);
            }
            return webReturn(200,"无效",['login'=>false]);
        }catch (\Exception $e){
            return webReturn(200,$e->getMessage(),['login'=>false]);
        }
    }
}
