<?php
namespace app\common\model;
use think\Model;
use app\common\model\CourseStudent;

/**
 * 
 */

class AdminStudent extends Model
{
	protected $dateFormat = 'Y年m月d日';    // 日期格式

    /**
     * 自定义自转换字换
     * @var array
     */
    protected $type = [
        'create_time' => 'datetime',
        'update_time' => 'datetime',
    ];
    public function Courses()
    {
        return $this->belongsToMany('Admincourse',config('datebase.prefix') . 'Admincourse_student');
    }


    //获取是否存在相关信息
     public function getIsChecked(Course &$AdminCourse)
    {
        // 取学生ID
        $studentId = (int)$this->id;
        $courseId = (int)$Course->id; 

        // 定制查询条件
        $map = array();
        $map['course_id'] = $courseId;
        $map['student_id'] = $studentId;

        // 从关联表中取信息
        $CourseStudent = AdminCourseStudent::get($map);
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
    public function AdminCourse()
    {
        return $this->belongsToMany('AdminCourse');
    }

    public function AdminCourseStudent()
    {
        return $this->hasMany('AdminCourseStudent');
    }
    public function AdminCourseStudents()
    {
    	return $this->hasMany('AdminCourseStudent');
    }

    public function getName()
    {
        return $this->name;
    }
}
