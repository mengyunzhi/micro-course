<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类

class Teacher extends Validate
{
    protected $rule = [
    	'username'=>'require|unique:teacher|length:2,25',
    	'password'=>'require',
        'name'=>'require|length:2,25',
    ];
}