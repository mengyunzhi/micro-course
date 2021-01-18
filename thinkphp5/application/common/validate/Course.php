<?php
namespace app\common\validate;
use think\Validate;
class Course extends Validate
{
	 protected $rule = [
        'name'=>'require|length:2,25',
        'usmix' => 'require'
    ];

    protected $message = [
    	'name.require' => '要求姓名长度在2位到25位之间',
    	'usmix' => '签到占比不能为空'
    ];
}