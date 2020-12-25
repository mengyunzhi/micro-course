<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Classroom;
use app\common\model\Teacher;


/**
 * 
 */
class PreClassController extends IndexController
{
     public function index()
    {

        // 获取老师对应的ID
        $id =session('teacherId');
        $classroomId = Request::instance()->param('classroomId');

        //实例化Classroom对象
        $Classroom = Classroom::get($classroomId);

        //增加判断是否是在上课签到时间
        if ($Classroom->$beginTime < time() && $Classroom->$outTime > time()) {
            
        }

        // 实例化老师	
        $Teacher = Teacher::get($id);

        //获取该老师对应的课程信息
        $Courses = Course::where('teacher_id','=',$id)->select();

        $this->assign('courses',$Courses);
        $this->assign('Teacher',$Teacher);

        return $this->fetch();
    }
}
            