<?php
namespace app\common\model;
use think\Model;
class Teacher extends Model{
	protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 自定义自转换字换
     * @var array
     */
    protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
	 public function courses()
    {
        return $this->belongsToMany('Course',  config('database.prefix') . 'course');
    }
}