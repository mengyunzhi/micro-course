<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
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

        // 实例化老师	
        $Teacher = Teacher::get($id);

        //获取该老师对应的课程信息
        $Courses = Course::where('teacher_id','=',$id)->select();

        $this->assign('courses',$Courses);
        $this->assign('Teacher',$Teacher);

        return $this->fetch();
    }
}
            