<?php namespace App\Http\Controllers\xcx;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/2/28 Time: 1:19 下午
 *
 ****************************************************/
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class LoginBaseController extends Controller{
    public function __construct(Request $baseReq)
    {
        addLog($baseReq);

    }


}
