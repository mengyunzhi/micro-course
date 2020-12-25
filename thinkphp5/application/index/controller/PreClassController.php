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
     public function index() {

        // 获取老师对应的ID
        $id =session('teacherId');
        // 暂时调整教室id为1
        // $classroomId = Request::instance()->param('classroomId');
        $classroomId = 1;
 
        // 实例化Classroom对象
        $Classroom = Classroom::get($classroomId);

        // 增加判断是否是在上课签到时间
        if (!is_null($this->isSign($Classroom, $id))) {
            return $this->success('当前正在已在签到时间', url('Onclass/index?classroomId=' . $Classroom->id . '&courseId=' . $Classroom->Course->id . '&beginTime=' . $Classroom->begin_time . '&outTime=' . $Classroom->out_time));
        }

        // 实例化老师	
        $Teacher = Teacher::get($id);

        // 获取该老师对应的课程信息
        $Courses = Course::where('teacher_id','=',$id)->select();

        $this->assign('courses',$Courses);
        $this->assign('Teacher',$Teacher);

        return $this->fetch();
    }

    /**
    * 判断该教室是否是在签到时间
    */
    protected function isSign(Classroom &$Classroom,$teacherId) {
        if ($Classroom->begin_time < time() && $Classroom->out_time > time()) {
            if ($teacherId == $Classroom->Course->Teacher->id) {
                return 1;
            }
        } else {
            return null;
        }
    }
}
            