<?php
namespace app\common\model;
use think\Model;
/**
 * 班级
 */
class AdminCourse extends Model
{
	protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 自定义自转换字换
     * @var array
     */
	private $Teacher;
    /**
     * 获取对应的教师（辅导员）信息
     * @return Teacher 教师
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function AdminTeacher()
    {
        return $this->belongsTo('AdminTeacher');
    }
}