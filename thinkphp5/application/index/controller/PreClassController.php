<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Classroom;
use app\common\model\Teacher;
use app\common\model\InClass;
use app\common\model\Seat;

/**
 * 课前管理部分，负责统计课前签到时间设置及签到课程选择
 */
class PreClassController extends IndexController
{
     public function index() {
        // 获取老师对应的ID,实例化教师对象
        $id = session('teacherId');
        $Teacher = Teacher::get($id);

        // 暂时调整教室id为1
        // $classroomId = Request::instance()->param('classroomId');
        $classroomId = $Teacher->classroom_id;
        if (is_null($classroomId) || $classroomId === 0) {
            return $this->error('请首先用微信扫描教室讲桌二维码', url('Course/index'));
        }
 
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
                return $this->success('当前处于上课时间', url('InClass/index?reclass=' . 1 . '&classroomId=' . $Classroom->id));
            } 
        }

        // 删除上节课的信息
        if (!$this->clearClassroom($Classroom)) {
                return $this->error('上一次上课信息清除失败,请重新上课', url('PreClass/index'));
        }
    }

    /**
    * 清除教室中保留的上节课信息
    * @param $Classroom 被清除教室对象
    */
    protected function clearClassroom(Classroom &$Classroom) {
        // 实例化请求
        $Request = Request::instance();

        // 构造查询条件数组,根据教室id和是否被坐找出被坐座位
        $que = array(
            "classroom_id"=>$Classroom->id,
            "is_seated"=>1
        );
        
        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->select();

        // 调用clearSeats方法对已做座位进行信息清空
        $this->clearSeats($Seats);

        // 将该教室对象的各个数据进行清空
        $Classroom->begin_time = 0;
        $Classroom->out_time = 0;
        $Classroom->course_id = 0;
        $Classroom->sign_time = 20;
        $Classroom->sign_deadline_time = 0;
        $Classroom->update_time = time();
        $Classroom->out_time = 0;
        $Classroom->begin_time = 0;
        $Classroom->sign_begin_time = 0;

        // 更新并保存数据
        $Classroom->validate(true)->save();
        return 1;
    }

    /**
    * 清除教室对应的座位的座位信息
    * @param $Seats 将被清除的座位对象数组
    */
    protected function clearSeats(array &$Seats) {
        // 将该教室的各个座位的信息清空
        // 首先得出该教室中的座位个数
        $number = sizeof($Seats);

        // 对该教室的每个座位信息进行逐个清空
        for ($i = 0; $i < $number; $i++) {
            $Seats[$i]->is_seated = 0;
            $Seats[$i]->student_id = 0;
            if (!$Seats[$i]->validate(true)->save()) {
                return $this->error('座位信息重置失败', $Request->header('referer'));
            }
        }
    }
}
            