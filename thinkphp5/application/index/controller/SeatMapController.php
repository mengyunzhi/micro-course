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
		return $this->fetch();
	}
	public function add(){
		return $this->fetch();
	}
	public function edit(){
		return $this->fetch();
	}
}