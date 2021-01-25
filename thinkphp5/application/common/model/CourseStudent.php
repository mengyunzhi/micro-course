<?php
namespace app\common\model;
use think\Model;

class CourseStudent extends Model
{
    public function student()
    {
        return $this->belongsTo('student');
    }

    /**
     * 获取对应的课程
     */
    public function course()
    {
        return $this->belongsTo('course');
    }
}