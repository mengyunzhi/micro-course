<?php
namespace app\common\model;
use think\Model;
use think\Request;
/**
 * 班级
 */
class Course extends Model
{
    /**
     * 获取对应的教师（辅导员）信息
     * @return Teacher 教师
     * @author <panjie@yunzhiclub.com> http://www.mengyunzhi.com
     */
    public function Teacher()
    {
            return $this->belongsTo('teacher');
    }
    public function Students()
    {
        return $this->belongsToMany('Student',config('database.prefix').'course_student');
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

    public function Term()
    {
        return $this->belongsTo('term');
    }

    /**
     * 构造函数：负责老师权限处理
     */
    public function checkTeacher()
    {
        // 获取session中存储的教师id
        $teacherId = session('teacherId');
        if ($this->Teacher->id !== $teacherId) {
            return $this->error('无此权限', request()->header('referer'));
        }
    }

    /**
     * 获取该课程的上课课程对象数组
     */
    public function classCourses()
    {
        // 调用hasmany函数获取一对多情况下的上课课程对象数组
        return $this->hasMany('class_course');
    }
}