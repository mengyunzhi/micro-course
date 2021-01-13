<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类

class Teacher extends Validate
{
    protected $rule = [
    	'username'=>'require|unique:teacher|length:6,25',
        'name'=>'require|length:2,25',
    ];
}