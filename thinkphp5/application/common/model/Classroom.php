<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * classroom 教室表
 */
class Classroom extends Model
{
    protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 自定义自转换字换
     * @var array
     */
   /* protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];*/

    /**
    * 获取对应课程的函数
    */
    public function Course()
    {
    	return $this->belongsto('course');
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
    /**
     * 获取要显示的更新时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getUpdateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
}