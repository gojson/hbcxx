<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function _exit403(){
        header('HTTP/1.1 403 Forbidden');
        header('Content-Type:application/json');
        echo json_encode(['status'=>403,'msg'=>"未登录"]);
        die;
    }
}
