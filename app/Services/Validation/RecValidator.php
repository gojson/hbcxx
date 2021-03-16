<?php namespace Services\Validation;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/3 Time: 11:27 上午
 * 注册用户验证器
 ****************************************************/

class RecValidator extends Validator
{

    /**
     * @var array Validation rules for the test form, they can contain in-built Laravel rules or our custom rules
     */
    protected $rules = array(
        'name' => 'required|between:2,10',
        'tel' => array('required', 'numeric', 'digits_between:11,11'),
        'position' => array('required', 'alpha_dash', 'between:2,32'),
        'company' => array('required', 'between:2,128'),
        'num'       => array('required','numeric','min:1'),
        'want'       => array('required'),
    );

    protected $messages =
        [
            'name.required' => '姓名必填',
            'name.between'  => '姓名长度2~10位',
            'tel.required'  => '手机号必填',
            'tel.numeric'  => '手机号必须是数字',
            'tel.digits_between'  => '手机号长度11位',
            'position.required'  => "职位必填",
            'position.between'  => "职位长度2~32位",
            'company.required'  => "公司必填",
            'company.between'  => "公司名称长度2~32位",
            'num.required'  => "公司规模必填",
            'num.numeric'    => "请填写正确的公司规模",
            'num.min'       => "请填写正确的公司规模",
            'want.required'       => "请填写客户需求",
        ];
}
