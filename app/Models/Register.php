<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/3 Time: 1:52 下午
 *
 ****************************************************/
class Register extends Model{
    protected  $guarded = [
        'admin'
    ];
    #public $timestamps = FALSE;

    const STAT_DEFAULT = 0;//待审核
    const STAT_SUC = 1;//审核通过
    const STAT_REFUSE = 2;//审核拒绝

    const IS_ADMIN = 1;//管理员
    public function test(){
        return $this->first();
    }
}
