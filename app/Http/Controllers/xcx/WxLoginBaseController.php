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
    }

    public function _checkLogin($baseReq){
        $this->_openid = $baseReq->input('openid','');
        $this->_uid = $baseReq->input('uid','');
    }
}
