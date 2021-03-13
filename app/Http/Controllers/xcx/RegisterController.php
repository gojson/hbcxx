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
            $input['openid'] = $this->_openid;
            $input['uid'] = $this->_uid;
            if(\App\Models\Register::where('openid','=',$this->_openid)->exists()){
                throw new \Exception("此微信已注册,不能重复注册");
            }
            $this->_validator->validate( $input );
            if(\App\Models\Register::where('tel','=',$input['tel'])->exists()){
                throw new \Exception("该手机号已被注册");
            }
            if(!\App\Models\ComUser::where('tel','=',$input['tel'])->exists()){
                throw new \Exception("该手机号不属于公司员工");
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
                throw new \Exception("请填写账号");
            }
            if(!isset($input['pwd'])){
                throw new \Exception("请填写密码");
            }
            $where = [
                ['openid','=',$this->_openid],
                ['tel','=',$input['tel']],
                ['pwd','=',$input['pwd']],
            ];
            $find = \App\Models\Register::where($where)->first();
            if(!$find){
                throw new \Exception("账号或密码错误");
            }
            if($find['stat']!= \App\Models\Register::STAT_SUC){
                throw new \Exception("账号尚未通过审核");
            }
            $this->setLogin($req,$find['id']);
            return webReturn(200,'登录成功',['is_admin'=>$find->admin]);
        }  catch ( \Exception $e ) {
            return webReturn(-1,$e->getMessage());
        }
    }

    private function setLogin($req,$id){
        session(['reg_id'=>$id]);
        session()->save();
    }

    public function logout(){
        session()->flush();
        return webReturn(200,'已退出登录');
    }
}
