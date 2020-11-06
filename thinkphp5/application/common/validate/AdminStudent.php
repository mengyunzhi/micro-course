<?php
namespace app\common\validate;
use think\Validate;
class AdminStudent extends Validate
{
protected $rule = [
        'name'=>'require|length:2,25',
        'num'=>'require|length:6',
    ];
}
