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
}
