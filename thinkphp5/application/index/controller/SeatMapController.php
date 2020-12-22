<?php
namespace app\index\controller;
use think\Controller;
use app\common\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Seat;
class SeatMapController extends Controller{
	
	public function index(){
		$id = Request::instance()->get('id\d');
		if(!is_null($id)){
			$SeatMap = SeatMap::where('id',"=",$id)->select();
		}
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