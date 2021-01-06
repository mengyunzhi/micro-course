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
 * 课前管理部分，负责统计课前签到时间设置及签到课程选择
 */
class PreClassController extends IndexController
{
     public function index() {

        // 获取老师对应的ID
        $id = session('teacherId');

        // 暂时调整教室id为1
        // $classroomId = Request::instance()->param('classroomId');
        $classroomId = 35;
 
        // 实例化Classroom对象
        $Classroom = Classroom::get($classroomId);

        // 增加判断是否是在上课签到时间
        $this->isSign($Classroom, $id);

        // 获取当前时间
        $thisTime = time();

        // 实例化老师	
        $Teacher = Teacher::get($id);

        // 获取该老师对应的课程信息
        $Courses = Course::where('teacher_id', '=' , $id)->select();

        $this->assign('courses', $Courses);
        $this->assign('Teacher', $Teacher);

        return $this->fetch();
    }

    /**
    * 判断该教室是否是在签到时间
    * @param Classroom 教室对象
    * @param teacherId 教师对应的id
    */
    protected function isSign($Classroom, $teacherId) {
        // 增加判断当前时间和下课截止时间的关系，如果未到下课时间，则再判断是否对应上课老师
        // 首先将时间戳转换为小时:分钟的形式再进行比较
        $currentTime = time();
        if ($Classroom->out_time > $currentTime && $currentTime > $Classroom->begin_time) {
            // 增加判断是否老师为当前签到时间对应的老师
            if ($teacherId === $Classroom->Course->Teacher->id) {
                return $this->success('当前处于签到时间', url('InClass/index?reclass=' . 1 . '&classroomId=' . $Classroom->id));
            }

        }
    }
}
            