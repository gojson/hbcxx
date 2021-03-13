<?php namespace App\Http\Controllers\xcx;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/13 Time: 1:23 下午
 * 公共信息
 ****************************************************/
class CommonController extends Controller{

    /**
     * 首页
     * author: chenyuanliang
     * $param
     * @return
     */
    public function index(){
        try{
            $bannerList = \App\Models\IndexBanner::where('id','>',0)->orderBy('id','desc')->get()->toArray();
            $introList = \App\Models\IndexIntro::where('id','>',0)->orderBy('id','desc')->get()->toArray();
            $data = [
                'bannerList' => $bannerList,
                'introList' => $introList,
            ];
            return webReturn(200,"ok",$data);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * author: chenyuanliang
     * $param
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     * @return
     */
    public function intro(Request $req){
        try{
            $basic = \App\Models\Basic::first();
            $data = [
                'basic' => $basic,
            ];
            return webReturn(200,"ok",$data);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * author: chenyuanliang
     * $param
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     * @return
     */
    public function news(Request $req){
        try{
            $list = \App\Models\News::get()->toArray();
            $data = [
                'list' => $list,
            ];
            return webReturn(200,"ok",$data);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

}
