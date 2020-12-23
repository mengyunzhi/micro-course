<?php
namespace app\index\controller;
use think\Controller;
use app\common\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\PreClass;
use app\common\model\Teacher;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\Seat;
class SeatMapTeacherController extends Controller {
	
	public function index() {
		
		// 获取老师对应的ID
        $id =session('teacherId');

        //接受出入的时间值
        $beginTime = Request::instance()->param('beginTime');
        $course_id = Request::instance()->param('course_id');
        $outTime = Request::instance()->param('outTime');
        $beginTime=strtotime($beginTime);
        $outTime=strtotime($outTime);

        // 实例化preclass和course，方便返回错误信息
        $Preclass = new PreClass;
        $Course = Course::get($course_id);

        // 增加判断签到时间必须设置
        if($beginTime == ''||$outTime == '')
        {
            return $this->error('签到时长不得为空'.$Preclass->getError());
        }
        // 增加判断签到时长不得大于两个小时
        if($outTime - $beginTime >= 7200) {
            return $this->error('签到时长不得高于两个小时'.$Preclass->getError());
        }

        // 增加判断开始签到时间不得大于截止签到时间
        if($beginTime>=$outTime)
        {
        	return $this->error('签到开始时间大于签到截止时间'.$Preclass->getError());
        }

        //将时间戳转换为自己想要的时间
        $beginTime = date('Y年m月d日H时i分',$beginTime);
        $outTime = date('Y年m月d日H时i分',$outTime);

        //获取已签到学生（由于还未完整完成数据传输，此时首先调用所有学生信息进行调试）
        $Students = Student::all();

        //获取学生们对应的学号，方便进行随机点名
        for($i=0;$i<sizeof($Students);$i++)
        {
        	$nums[$i] = $Students[$i]->num; 
        }

        //传入已签到学生

        // 实例化老师	
        $Teacher = Teacher::get($id);
		// 实例化课程
		$Courses = Course::where('teacher_id','=',$id)->select();

		$this->assign('Course',$Course);
		$this->assign('nums',$nums);
		$this->assign('beginTime',$beginTime);
		$this->assign('outTime',$outTime);
		return $this->fetch();
	}
	public function add() {
		return $this->fetch();
	}
	public function edit() {
		return $this->fetch();
	}
	public function template1() {
		return $this->fetch();
	}
	public function template2() {
		return  $this->fetch();
	}
    public function afterclass() {
        // 接收教室编号

        // 对该教室所有座位进行初始化，使其状态为未被坐

        // 需要跳转到course/index
    }
    //原型的点名
	public function call(){
		$id = 92;
		$course_id = 3;
		// 实例化课程
		$Course = Course::get($course_id);

		$this->assign('Course',$Course);
		return $this->fetch();
	}
    //用来测试座位图随机点名
	public function test() {
		return $this->fetch();
	}
}