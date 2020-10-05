<?php
namespace app\common\model;
use think\model;
use app\common\model\CourseStudent;

class Student extends Model
{
    public function Courses()
    {
        return $this->belongsToMany('Course',config('database.prefix').'course_student');
    }

    public function Course()
    {
        return $this->belongsTo('course');
    }

    //获取是否存在相关信息
     public function getIsChecked(Course &$Course)
    {
        // 取课程ID
        $studentId = (int)$this->id;
        $courseId = (int)$Course->id; 

        // 定制查询条件
        $map = array();
        $map['course_id'] = $courseId;
        $map['student_id'] = $studentId;

        // 从关联表中取信息
        $CourseStudent = CourseStudent::get($map);
        if (is_null($CourseStudent)) {
            return false;
        } else {
            return true;
        }
    }

    private $Course;

    /**
     * 获取对应的教师（辅导员）信息
     * @return Teacher 教师
     * @author <panjie@yunzhiclub.com> http://www.mengyunzhi.com
     */
    public function CourseStudents()
    {
        return $this->hasMany('CourseStudent');
    }

    public function getName()
    {
        return $this->name;
    }

    
}