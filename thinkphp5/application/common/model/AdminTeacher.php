<?php
namespace app\common\model;
use think\Model;
class AdminTeacher extends Model{
	protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 自定义自转换字换
     * @var array
     */
    protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
	 public function Admincourses()
    {
        return $this->belongsToMany('AdminCourse',  config('database.prefix') . 'Admincourse');
    }
}