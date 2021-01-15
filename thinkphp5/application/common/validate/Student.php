<?php
namespace app\common\validate;
use think\Validate;
class Student extends Validate
{
 	protected $rule = [
    		'username'=>'require|unique:teacher|length:4,25',
        	'name'=>'require|length:2,25',
    	];
}