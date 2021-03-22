<?php namespace App\Http\Controllers\xcx;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/2/28 Time: 1:20 下午
 *
 ****************************************************/
use Illuminate\Http\Request;
class UserController extends \App\Http\Controllers\xcx\WxLoginBaseController{

    /**
     * 保存/更新手机号
     * @param $_POST['iv']
     * @param $_POST['encryptedData']
     * */
    public function saveTel(Request $req){
        $where = [
            ['openid','=',$this->_openid],
        ];
        $field = 'appid,session_key,old_session_key';
        $user      = \App\Models\User::select($field)->where($where)->first();
        $appId         = $user->appid;
        $sessionKey    = $user->session_key;
        if( $appId && $sessionKey ){
            $telInfo = [];
            $iv            = $req->input('iv', '', 'trim');
            $encryptedData = $req->input('encryptedData', '', 'trim');
            $decRes = decryptData($encryptedData, $iv, $sessionKey, $appId, $telInfo);  # 成功后会返回0
            if($decRes !==0){
                $this->_exit403();
            }
            $user->tel = $telInfo['phoneNumber'];
            $user->auth_tel_at = now();
            if($user->save()){
                return webReturn(200,'更新成功');
            }
        }
        return webReturn(403,'更新失败');
    }

    /**
     * 保存/更新用户信息
     * @param $_POST['iv']
     * @param $_POST['signature']
     * @param $_POST['encryptedData']
     * */
    public function saveAuth(Request $req){
        try{
            $field          = ["appid","session_key"];
            $user           = \App\Models\User::select($field)->where('openid','=',$this->_openid)->first();
            $appId          = $user->appid;
            $sessionKey     = $user->session_key;
            if( !$appId || !$sessionKey ) {
                throw new \Exception("appid or session_key error");
            }
            $iv            = $req->input('iv', '', 'trim');
            $encryptedData = $req->input('encryptedData', '', 'trim');
            $rawData   = $req->input('rawData', '', 'trim');
            $signature = $req->input('signature', '', 'trim');
            $tmpSig    = sha1($rawData . $sessionKey);              # 签名校验
            if( $tmpSig  != $signature) {
                throw new \Exception("sig error");
            }
            $userInfo = getDecUserInfo($encryptedData, $iv, $sessionKey, $appId);
            if(empty($userInfo)){
                throw new \Exception("用户授权失败");
            }
            $update = [
                'unionid'   => $userInfo['unionId']??"",
                'nickname'   => $userInfo['nickName'],
                'headimgurl' => $userInfo['avatarUrl'],
                'sex'        => $userInfo['gender'],
                'city'       => $userInfo['city'],
                'province'   => $userInfo['province'],
                'country'    => $userInfo['country'],
                'language'   => $userInfo['language'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            \App\Models\User::where('id','=',$user->id)->update($update);
            return webReturn(200,'ok');
        }catch (\Exception $e){
            return webReturn(403,$e->getMessage());
        }
    }

    /**
     * 读取用户信息
     * author: chenyuanliang
     * $param
     * @param \Illuminate\Http\Request $req
     * @return
     */
    public function getUserInfo(Request $req){
        try{
            $userInfo = \App\Models\User::where('openid','=',$this->_openid)->first();
            return webReturn(200,'ok',['userInfo'=>$userInfo]);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }
}
