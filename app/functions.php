<?php
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/2/28 Time: 1:40 下午
 *
 ****************************************************/
/**
 * 检查token签名
 * @param $token
 * @param $zmSignature
 * */
function checkSignature($token, $zmSignature){
    return $zmSignature == generalSignature($token);
}
function generalSignature($token){
    $hashKey = config('consts.TOKEN_SIGNATURE_KEY');
    return sha1(md5("{$token}-{$hashKey}"));
}

/**
 * @param $encryptedData 加密后的字符串
 * @param $iv            前端传入iv
 * @param $sessionKey    用户授权的sessionKey
 * @param $appId         小程序的 appid
 * */
function getDecUserInfo($encryptedData, $iv, $sessionKey, $appId){
    $userInfo = [];
    if( $encryptedData && $iv && $sessionKey && $appId ){
        $res = decryptData($encryptedData, $iv, $sessionKey, $appId, $userInfo);
        return $res === 0 ? $userInfo : [];
    }
    return $userInfo;
}

/**
 * 对称解密微信 encryptedData
 * @param $encryptedData        加密后的字符串
 * @param $iv                   前端传入的Iv
 * $param $sessionKey           用户授权的 sessionKey
 * $param $appId                小程序appid
 * @param $data                 解密后的数据
 * @return [] 解密后的数据，数组形式
 * */
function decryptData($encryptedData, $iv, $sessionKey, $appId, &$data){
    if($encryptedData && $iv && $sessionKey && $appId ){
        if( strlen($sessionKey) == 24 ){
            if( strlen($iv) == 24 ){
                $aesKey    = base64_decode($sessionKey);
                $aesIV     = base64_decode($iv);
                $aesCipher = base64_decode($encryptedData);
                $result    = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
                $resultArr = json_decode($result, true);
                if( NULL != $resultArr){
                    if( $resultArr['watermark']['appid'] == $appId ){
                        $data = $resultArr;
                        return 0;
                    }
                    return -41003;
                } // end NULL != $dataObj
                return -41004;
            } // end strlen($iv)
            return -41002;
        } // end strlen($sessionKey)
        return -41001;
    } // $encryptedData ...
    return -41005;
}

if(!function_exists('webReturn')){
    function webReturn($status=0,$msg='成功',$data=[],$code=200){
        return response()->json(['status'=>$status,'msg'=>$msg,'data'=>$data],$code,[],256);
    }
}

function addLog(\Illuminate\Http\Request $req,$fileName="logFile"){
    $log = [
        'datetime'  => date("Y-m-d H:i:s"),
        'url'       => $req->getRequestUri(),
        'input'     => $req->input(),
        'header'    => $req->header(),
    ];
    file_put_contents($fileName,json_encode($log)."\t\n",FILE_APPEND);
}
