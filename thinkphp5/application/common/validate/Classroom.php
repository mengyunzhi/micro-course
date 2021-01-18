<?php
namespace app\common\validate;
use think\Validate;//内置验证类

class Classroom extends validate {
	protected $rule = [
        'name'=>'require|length:2,25',
    ];

    protected $message = [
    	'name.require' => '名字长度不得小于2',
    ];
}