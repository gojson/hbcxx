<?php namespace App\Http\Controllers\xcx;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/2 Time: 8:24 下午
 * 推荐人
 ****************************************************/
use Services\Validation\RecValidator;
use \Illuminate\Http\Request;
class RecController extends \App\Http\Controllers\xcx\LoginBaseController{

    protected $_validator = "";

    public function __construct( RecValidator $validator )
    {
        $this->_validator = $validator;
    }

    /**
     * 添加推荐人
     * author: chenyuanliang
     * $param
     * @return
     */
    public function store(Request $req){
        try {
            $input = $req->all();
            $input['reg_id'] = session('reg_id');
            if(\App\Models\Rec::where('tel','=',$input['tel'])->exists()){
                throw new \Exception("此客户已被推荐过,不能重复推荐");
            }
            $this->_validator->validate( $input );
            $id = \App\Models\Rec::create($input);
            if(!$id){
                throw new \Exception("创建失败");
            }
            return webReturn(200,'创建成功');
        } catch ( \Services\Exceptions\ValidationException $e ) {
            return webReturn(-1,$e->get_errors()->first());
        }catch ( \Exception $e ) {
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * 我的推荐人列表
     * author: chenyuanliang
     * $param
     * @param \Request $req
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     * @return
     */
    public function recList(Request $req){
        try{
            $list = \App\Models\Rec::where('stat',$req->input('stat'))->orderBy('id','desc')->get()->toArray();
            foreach($list as $k=>$val){
                $list[$k]['stat_text'] = \app\Models\Rec::statMapList()[$val['stat']];
            }
            return webReturn(200,"ok",['list'=>$list]);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * 被推荐人stat
     * author: chenyuanliang
     * $param
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     * @return
     */
    public function recStat(Request $req){
        try{
            return webReturn(200,"ok",['list'=>\app\Models\Rec::statMapList()]);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }
}
