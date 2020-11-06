<?php
namespace app\common\validate;
use think\Validate;    //内置验证类

/**
 * 
 */
class AdminTeacher extends Validate
{
	
	protected $rule = [
        'name'=>'require|length:2,25',
        'teacher_id'=>'require|length:6',
];
}