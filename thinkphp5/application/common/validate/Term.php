<?php
namespace app\common\validate;
use think\Validate;    //内置验证类

/**
 * 
 */
class Term extends Validate
{
	
	protected $rule = [
		'name'=>'require|unique:term|max:25',
        'ptime'=>'require',
        'ftime'=>'require',
];
	 protected $message  =   [
        'name.require' => '学期名不能为空',
        'name.unique' => '学期名已存在',
        'name.max'     => '名称最多不能超过25个字符',
        'ptime.require'=>'起始时间不能为空',
        'ftime.require'=>'结束时间不能为空',

    ];
}