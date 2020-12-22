<?php
namespace app\index\controller;
use think\Controller;
use app\common\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Course;
use app\common\model\Seat;
class SeatMapController extends Controller{
	
	public function index(){
		$id = Request::instance()->get('id\d');
		$course_id = Request::instance()->param('course_id');
		// 实例化课程
		$Course = Course::get($course_id);

		$this->assign('Course',$Course);
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
}