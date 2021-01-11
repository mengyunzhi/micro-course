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
 * 负责微信端教师界面的显示
 */
class WxTeacherController extends IndexController {
	
	/**
     * 微信端扫码登陆后的跳转到上课表现成绩加减分部分
     */
    public function wxAod() {
        // 接收教室id和教师id,并实例化教室对象和教师对象
        $teacherId = session('teacherId');
        $Teacher = Teacher::get($teacherId);
        $classroomId = $Teacher->classroom_id;
        $Classroom = Classroom::get($classroomId);

        // 根据教室获取课程id
        $courseId = $Classroom->course_id;
        $Course = Course::get($courseId);

        // 通过教室获取当前正在上课的学生名单(因为学生扫码有判断是否为本班学生，故不必判断)
        $seats = Seat::where('classroom_id', '=', $classroomId)->select();

        $this->assign('seats', $seats);
        $this->assign('Teacher', $Teacher);
        $this->assign('Course', $Course);

        return $this->fetch();
    }	

    /**
     * 负责微信的加减分项选择
     */
    public function gradeChange() {
    	// 接收座位id和课程id和加减分判断aodStateId，并进行实例化
    	$seatId = Request::instance()->param('seatId/d');
    	$courseId = Request::instance()->param('courseId/d');
    	$aodState = Request::instance()->param('aodState/d');
    	$Seat = Seat::get($seatId);
    	$Course = Course::get($courseId);
    	if (is_null($Seat)) {
    		return $this->error('学生信息不存在', url('wxAod?teacherId=' . $Course->Teacher->id));
    	}

    	// 通过课程id和学生id获取成绩对象
    	$Grade = Grade::get(['course_id' => $Course->id, 'student_id' => $Seat->student->id]);
    	if (is_null($Grade)) {
    		return $this->error('学生信息不存在', url('wxAod?teacherId=' . $Course->Teacher->id));
    	}

    	// 通过加减分判断和课程id获取对应存在的加减分项
    	$gradeAods = Gradeaod::where(['aod_state' => $aodState, 'course_id' => $courseId]);

    	$this->assign('Grade', $Grade);
    	$this->assign('gradeAods', $gradeAods);

    	return $this->fetch();
    }

    /**
     * 微信端的加减分保存操作
     */
    public function update() {
    	// 接收加减分项id和对应的成绩id
    	// 实例化对象请求
    	$Request = Request::instance();
    	$gradeId = Request::instance()->param('gradeId/d');
    	$gradeAodId = Request::instance()->param('gradeAodId/d');

    	// 实例化成绩对象和加减分对象
    	$Grade = Grade::get($gradeId);
    	$GradeAod = Gradeaod::get($gradeAodId);

    	// 加减分操作并保存更改后的成绩
    	$Grade->coursegrade + = $GradeAod->aod_num;
    	if (!$Grade->save()) {
    		return $this->error('成绩修改失败，请重新操作' . $Grade->getError());
    	} 
    	return $this->success('成绩修改成功', url('wxAod'));
    }
}