<?php namespace App\Models;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/2 Time: 8:27 下午
 * 推荐人
 ****************************************************/
class Rec extends \Illuminate\Database\Eloquent\Model{

    protected $guarded = [];

    public  static function statMapList(){
        $map = [
            0 => "待审核",
            1 => '已审核',
            2 => "跟进中",
            3 => "成交",
        ];
        return $map;
    }
}
