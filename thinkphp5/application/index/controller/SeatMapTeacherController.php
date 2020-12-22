<?php
namespace app\index\controller;
use think\Controller;
use app\common\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Teacher;
use app\common\model\Course;
use app\common\model\Seat;
class SeatMapTeacherController extends Controller{
	
	public function index(){
		
		// 获取老师对应的ID
        $id =session('teacherId');

        // 实例化老师	
        $Teacher = Teacher::get($id);
		// 实例化课程
		$Courses = Course::where('teacher_id','=',$id)->select();
		
		$this->assign('courses',$Courses);
		return $this->fetch();
	}
	public function add(){
		return $this->fetch();
	}
	public function edit(){
		return $this->fetch();
	}
	public function template1(){
		return $this->fetch();
	}
	public function template2(){
		return  $this->fetch();
	}
	public function call(){
		$id = 92;
		$course_id = 3;
		// 实例化课程
		$Course = Course::get($course_id);

		$this->assign('Course',$Course);
		return $this->fetch();
	}
}