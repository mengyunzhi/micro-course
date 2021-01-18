<?php
namespace app\common\validate;
use think\Validate;     // 内置验证类

class Teacher extends Validate
{
    protected $rule = [
        'name'=>'require|length:2,25',
    	'username'=>'require|unique:teacher|length:4,25',
    	'num'=>'require|unique:teacher|length:6',
    ];
    protected $message = [
    	'name.require' => '姓名不能为空',
    	'name.length' => '姓名长度应位于2~25之间',
    	'username.require' => '用户名不能为空',
    	'username.unique' => '用户名已存在', 
    	'username.length' => '用户名长度应位于4~25之间',
    	'num.require' => '工号不能为空',
    	'num.unique' => '工号已存在', 
    	'num.length' => '工号长度应为6',
    ];
}