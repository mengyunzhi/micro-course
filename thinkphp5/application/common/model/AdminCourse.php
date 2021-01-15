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
    /**
     * 获取要显示的创建时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
}