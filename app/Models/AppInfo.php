<?php namespace App\Models;
	use Illuminate\Database\Eloquent\Model;

    /***************************************************
	 * Created by PhpStorm.
	 * Author: json -- chenyuanliang@zmeng123.com
	 * Date: 2021/2/28 Time: 2:24 下午
	 *
	 ****************************************************/
class AppInfo extends Model{

    public function getAppInfoByAppid($appid){
        return $this->where('appid','=',$appid)->first();
    }
    /**
     * 根据 appid 获取 accesstoken
     * @param $appId
     * @param $force 是否强制获取
     * */
    public function getAccessToken($appId) {
        $appInfo   = $this->where('appid',$appId)->first();
        if($appInfo['access_token'] && (time()-strtotime($appInfo['updated_at']))<3600) {
            return $appInfo['access_token'];
        }
        $appSecret = $appInfo['appsecret'];
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecret}";
        $resArr = json_decode(file_get_contents($url), true);
        if(!empty($resArr) && $resArr['access_token']){
            $accessToken = $resArr['access_token'];
            $update = [
                'access_token' => $accessToken,
                'expires_in' => $resArr['expires_in'],
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $this->where('appid', $appId)->update($update);
            return $accessToken;
        }else{
            throw new \Exception("accesstoken 获取失败");
        }
    }

}
