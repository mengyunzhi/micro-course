<?php
namespace app\common\validate;
use think\Validate;    //内置验证类

/**
 * 
 */
class Term extends Validate
{
	
	protected $rule = [
		'name'=>'require|unique:term|length:4,25',
        'ptime'=>'require|length:2,25',
];
}