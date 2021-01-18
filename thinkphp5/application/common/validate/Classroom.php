<?php
namespace app\common\validate;
use think\Validate;//内置验证类

class Classroom extends validate {
	 protected $rule = [
        'name'=>'require|max:25',
    ];

    protected $message  =   [
        'name.require' => '教室编号不能为空',
        'name.max'     => '教室编号最多不能超过25个字符',
    ];
}