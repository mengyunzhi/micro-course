<?php
namespace app\common\validate;
use think\Validate;
class Student extends Validate
{
protected $rule = [
        'name'=>'require|length:2,25',
        'num'=>'require|length:6',
    ];
}