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
		$id = Request::instance()->param('id');
		$seat = new Seat;
		$seat = Seat::where('seat_map_id','=',$id)->select();
		$this->assign('seats',$seat);
		$SeatMap = new SeatMap;
		$SeatMap = SeatMap::get($id);
		$this->assign('SeatMap',$SeatMap);
		return $this->fetch();
	}
	public function save(){
		$SeatMap = new SeatMap;
		$SeatMap->x_map = Request::instance()->post('x_map');
		$SeatMap->y_map = Request::instance()->post('y_map');
		if(!$SeatMap->save()){
			return $this->error('保存信息错误'.$SeatMap->getError());
		}
		$id = $SeatMap->id;
		return $this->success('请选择座位和过道',url('Seat/add?id='.$id));
	}
	public function template1(){

		return $this->fetch();

	}
	public function template2(){

		return  $this->fetch();

	}
}