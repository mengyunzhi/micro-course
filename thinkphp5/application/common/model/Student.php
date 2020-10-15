<?php
namespace app\common\model;
use think\Model;
use app\common\model\CourseStudent;

/**
 * 
 */

class Student extends Model
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
        return $this->belongsToMany('course',config('datebase.prefix') . 'course_student');
    }


    //获取是否存在相关信息
     public function getIsChecked(Course &$Course)
    {
        // 取学生ID
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
    public function Course()
    {
        return $this->belongsToMany('Course');
    }

    public function CourseStudent()
    {
        return $this->hasMany('CourseStudent');
    }
    public function CourseStudents()
    {
    	return $this->hasMany('CourseStudent');
    }

    public function getName()
    {
        return $this->name;
    }
}
