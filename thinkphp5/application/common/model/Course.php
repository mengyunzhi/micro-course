<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Student 学生表
 */
class Course extends Model
{
    public function students()
    {
        return $this->belongsToMany('Student',  config('database.prefix') . 'student');
    }

     /**
     * 获取要显示的创建时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 自定义自转换字换
     * @var array
     */
    protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
    
}