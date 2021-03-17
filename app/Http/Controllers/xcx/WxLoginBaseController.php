<?php namespace App\Http\Controllers\xcx;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/2/28 Time: 1:19 下午
 *
 ****************************************************/
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class WxLoginBaseController extends Controller{
    protected $_openid = "";
    protected $_uid = "";

    public function __construct(Request $baseReq)
    {
        addLog($baseReq);
        $this->_checkLogin($baseReq);
        #$this->_openid = '123';
        #$this->_uid = 1;
    }

    public function _checkLogin($baseReq){
        $token = $baseReq->header('token');
        $sig = $baseReq->header('sig');
        $dump = $baseReq->header('dump');
        if ($token && $sig && checkSignature($token, $sig, $dump)) {
            $userInfo = \App\Models\User::where("token",$token)->find();
            if(!empty($userInfo)){
                $this->_openid = $userInfo->openid;
                $this->_uid = $userInfo->id;
                return true;
            }
        }
        $this->_exit403();
    }
}
