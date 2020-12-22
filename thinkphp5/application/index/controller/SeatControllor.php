<?php
namespace app\index\controller;
use app\common\model\Seat;
use app\common\validate\Seat;
/**
 * 
 */
class SeatController extends controller
{
	

	public function Index(){
		$classroom_id = Request::instance()->param('id/d');
		$Seats = new Seat;
		if (!is_null($classroom_id)) {
			$Seats = Seat::where('classroom_id','=',$classroom_id)->select();
		}
		$this->assign('Seats',$Seats);
		return $this->fetch();
	}
	public function add(){
		return $this->fetch();
	}
	public function edit(){
		return $this->fetch();
	}
	//设置座位,当前状态是座位则编程过道，否则为座位
	public function is_seat(){
		$id = Request::instance()->param('id\d');
		$Seat = new Seat;
		$Seat = Seat::get($id);
		if($Seat->isseat == "1")
		$Seat->isseat = "0";
		else 
		$Seat->isseat = "1";
		$this->save();
	}
	//思路同上
	public function is_seated(){
		$id = Request::instance()->param('id\d');
		$Seat = new Seat;
		$Seat = Seat::get($id);
		if($Seat->isseated == "1")
		$Seat->isseated = "0";
		else 
		$Seat->isseat = "1";
		$this->save();
	}

}