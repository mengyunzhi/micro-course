<?php
namespace app\common\model;
use think\Model;
class Term extends Model{
    static $Term_id;
	protected $dateFormat = 'Y年m月d日';    // 日期格式
	protected $state = 0;

    /**
     * 自定义自转换字换
     * @var array
     */
   /* protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];*/
}