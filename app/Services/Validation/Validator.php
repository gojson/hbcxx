<?php namespace Services\Validation;
/***************************************************
 * Created by PhpStorm.
 * Author: json -- chenyuanliang@zmeng123.com
 * Date: 2021/3/3 Time: 11:13 上午
 *
 ****************************************************/
use Illuminate\Validation\Factory as IlluminateValidator;
use Services\Exceptions\ValidationException;
abstract class Validator{

    /**
     * @var Illuminate\Validation\Factory
     */
    protected $_validator;

    public function __construct(IlluminateValidator $validator)
    {
        $this->_validator = $validator;
    }

    public function validate(array $data, array $rules = array(), array $messages = array())
    {
        if (empty($rules) && !empty($this->rules) && is_array($this->rules)) {
            //no rules passed to function, use the default rules defined in sub-class
            $rules = $this->rules;
        }
        if (empty($messages) && !empty($this->messages) && is_array($this->messages)) {
            //no rules passed to function, use the default rules defined in sub-class
            $messages = $this->messages;
        }

        //use Laravel's Validator and validate the data
        $validation = $this->_validator->make($data, $rules, $messages);

        if ($validation->fails()) {
            //validation failed, throw an exception
            throw new ValidationException($validation->errors());

        }


        //all good and shiny
        return true;
    }

}
