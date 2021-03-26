<?php namespace App\Http\Controllers\xcx;
use App\Models\Register;
use Illuminate\Http\Request;

/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/3 Time: 3:24 下午
 * 管理员操作
 ****************************************************/

class AdminController extends LoginBaseController{

    public function __construct(Request $baseReq)
    {
        parent::__construct($baseReq);
    }

    /**
     * 注册人列表
     * author: chenyuanliang
     * $param
     * @param \Illuminate\Http\Request $req
     * @return
     */
    public function registerList(Request $req){
        try{
            $stat = $req->input('stat',-1);
            $where = [
                ['admin','<>',\App\Models\Register::IS_ADMIN],
            ];
            if($stat>=0){
                $where[] = ['stat','=',$stat];
            }
            $field = ["id","name","tel","position","company"];
            $list = \App\Models\Register::select($field)->where($where)->where($where)->orderBy('id','desc')->get()->toArray();
            return webReturn(200,"ok",['list'=>$list]);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * 审核注册人
     * author: chenyuanliang
     * $param
     * @return
     */
    public function checkRegister(Request $req){
        try{
            $regId = $req->input('reg_id');
            $regInfoCheck = \App\Models\Register::where('admin','<>',1)->find($regId);
            if(!$regInfoCheck){
                throw new \Exception("用户不存在");
            }
            $regInfoCheck->stat = $req->input('stat',0);
            $regInfoCheck->save();
            return webReturn(200,"已审核");
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * 推荐人信息
     * author: chenyuanliang
     * $param
     * @param \Request $req
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     * @return
     */
    public function recInfo(Request $req){
        try{
            $recInfo = \App\Models\Rec::where('id',$req->input('rec_id'))->first();
            return webReturn(200,"ok",['recInfo'=>$recInfo]);
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }

    /**
     * 审核推荐人
     * author: chenyuanliang
     * $param
     * @return
     */
    public function checkRec(Request $req){
        try{
            $recId = $req->input('rec_id');
            $recInfoCheck = \App\Models\Rec::find($recId);
            if(!$recInfoCheck){
                throw new \Exception("用户不存在");
            }
            $recInfoCheck->stat = $req->input('stat');
            $recInfoCheck->save();
            return webReturn(200,"已审核");
        }catch (\Exception $e){
            return webReturn(-1,$e->getMessage());
        }
    }
}
