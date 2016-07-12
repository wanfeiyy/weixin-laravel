<?php
namespace App\Facades;

use Illuminate\Validation\Validator;

class ValidatorResult
{
    /**
     * 表单验证结果处理
     * @param object $validator      Validator 函数返回的内容
     * @return array|bool   如果验证有错误返回一个数组，如果没有错误返回一个false
     */
    public function validatorHandle(Validator $validator)
    {
        if($validator->fails()){
            $error_message = $validator->errors()->all();
            return ['state'=>false,'error_code'=>2001,'error'=>$error_message];
        }
        return ['state'=>true,'data'=>[],'message'=>'This is a success message!'];
    }
}