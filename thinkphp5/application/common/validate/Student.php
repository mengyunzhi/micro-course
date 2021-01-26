<?php
namespace app\common\validate;
use think\Validate;
class Student extends Validate
{
 	protected $rule = [
        	'name'=>'require|max:25',
        	'num'=>'require|unique:student|length:6',
    	];

     protected $message  =   [
        'name.require' => '姓名不能为空',
        'name.max'     => '姓名最多不能超过25个字符',
        'num.require'   => '学号不能为空',
        'num.length'   => '学号应为6位',
    ];
}