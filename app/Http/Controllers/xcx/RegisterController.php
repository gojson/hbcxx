<?php namespace App\Http\Controllers\xcx;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/3 Time: 11:45 上午
 * 注册员工
 ****************************************************/
use Illuminate\Http\Request;
use Services\Validation\RegisterValidator;

class RegisterController extends WxLoginBaseController {

    protected $_validator;

    public function __construct(Request $req)
    {
        parent::__construct($req);
    }


    /**
     * 注册员工信息
     * author: chenyuanliang
     * $param
     * @return
     */
    public function store(Request $req,RegisterValidator $validator ){
        try {
            $this->_validator = $validator;
            $input = $req->all();
            $this->_validator->validate( $input );
            if(\App\Models\Register::where('tel','=',$input['tel'])->exists()){
                throw new \Exception("该员工号已被注册");
            }
            if(!\App\Models\ComUser::where('tel','=',$input['tel'])->exists()){
                throw new \Exception("该员工号不属于公司员工");
            }
            $id = \App\Models\Register::create($input);
            if(!$id){
                throw new \Exception("创建失败");
            }
            return webReturn(200,'创建成功');
        } catch ( \Services\Exceptions\ValidationException $e ) {
            return webReturn(-1,$e->get_errors()->first());
        } catch ( \Exception $e ) {
            return webReturn(-1,$e->getMessage());
        }
}

/**
 * 登录
     * author: chenyuanliang
     * $param
     * @param \Illuminate\Http\Request $req
     * @return \Illuminate\Http\JsonResponse|\think\response\Json
     * @throws \Exception
     * @return
     */
    public function login(Request $req){
        try {
            $input = $req->all();
            if(!isset($input['tel'])){
                throw new \Exception("请填写工号");
            }
            if(!isset($input['pwd'])){
                throw new \Exception("请填写密码");
            }
            $where = [
                ['tel','=',$input['tel']],
                ['pwd','=',$input['pwd']],
            ];
            $find = \App\Models\Register::where($where)->first();
            if(!$find){
                throw new \Exception("工号或密码错误");
            }
            if($find['stat']!= \App\Models\Register::STAT_SUC){
                throw new \Exception("工号尚未通过审核");
            }
            return webReturn(200,'登录成功',["regId"=>$find['id'],"isAdmin"=>$find['admin']==1?true:false]);
        }  catch ( \Exception $e ) {
            return webReturn(-1,$e->getMessage());
        }
    }
}
