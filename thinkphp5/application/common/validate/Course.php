<?php
namespace app\common\validate;
use think\Validate;    //内置验证类

/**
 * 
 */
class Course extends Validate
{
	
	protected $rule = [
		'name'=>'require|unique:term|length:4,25',
];
}