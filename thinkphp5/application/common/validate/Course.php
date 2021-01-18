<?php
namespace app\common\validate;
use think\Validate;
class Course extends Validate
{
	 protected $rule = [
        'name'=>'require|max:25',
    ];

    protected $message  =   [
        'name.require' => '课程名不能为空',
        'name.max'     => '课程名最多不能超过25个字符',
    ];
    
}