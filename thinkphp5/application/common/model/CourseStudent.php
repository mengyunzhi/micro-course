<?php
namespace app\common\model;
use think\Model;
class CourseStudent extends Model{
	protected $dateFormat = 'Y年m月d日';    // 日期格式

<<<<<<< HEAD
    /**
     * 自定义自转换字换
     * @var array
     */
    protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
=======
class CourseStudent extends Model
{
	public function student()
	{
		return $this->belongsTo('Student');
	}
>>>>>>> b269e44c154328feb357dcc1524f5011aaf396c5
}