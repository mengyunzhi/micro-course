<?php
namespace app\common\model;
use think\Model;
/**
 * 班级
 */
class Course extends Model
{
    private $Teacher;

    /**
     * 获取对应的教师（辅导员）信息
     * @return Teacher 教师
     * @author <panjie@yunzhiclub.com> http://www.mengyunzhi.com
     */
    public function getTeacher()
    {
        if (is_null($this->Teacher)) {
            $teacherId = $this->getData('teacher_id');
            $this->Teacher = Teacher::get($teacherId);
        }
        return $this->Teacher;
    }
    public function Students()
    {
        return $this->belongsToMany('Student');
    }
    public function CourseStudents()
    {
        return $this->hasMany('CourseStudent');
    }
    public function getIsChecked(Student &$Student)
    {
        // 取课程ID
        $courseId = (int)$this->id;
        $studentId = (int)$Student->id;

        // 定制查询条件
        $map = array();
        $map['student_id'] = $studentId;
        $map['course_id'] = $courseId;

        // 从关联表中取信息
        $CourseStudent = CourseStudent::get($map);
        if (is_null($CourseStudent)) {
            return false;
        } else {
            return true;
        }
    }

}
