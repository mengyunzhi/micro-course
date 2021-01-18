<?php
namespace app\common\validate;
use think\Validate;
class Student extends Validate
{
 	protected $rule = [
    		'username'=>'require|unique:student|length:4,25',
        	'name'=>'require|length:2,4',
        	'num' => 'require|unique:student|length:4,10',
        	'email' => 'email',
    	];
    protected $message = [
    	'name.require' => '姓名必须',
    	'name.length' => '保证长度在2位到4位之间',
    ];
}