<?php
namespace app\index\controller;
use app\common\model\Seat;
use app\common\model\SeatMap;
use think\Controller;
use think\Request;
use think\validate;
use app\index\controller\SeatMapController;

/**
 * 座位管理，负责座位的信息更新和信息重置等
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
	/**
	* 增加座位
	* @param $seatMapId 对应模板的id
	* @param $url 要跳到的链接
	*/
	public function add() {
	
	}
	/**
	 * 挨个copy座位
	 */
	public function saveSeat() {
		
	}

	public function edit(){
		return $this->fetch();
	}

	/**
	* 设置座位,当前状态是座位则编程过道，否则为座位.0为座位，1为过道，默认为0
	*/
	public function is_seat(){
		$id = Request::instance()->param('id\d');
		$Seat = new Seat;
		$Seat = Seat::get($id);

		if($Seat->state == "1") {

			$Seat->state = "0";
		}
		
		else {

			$Seat->state = "1";
		}
		
		$this->save();
	}
	//思路同上 1为有人，0为无人,默认为0
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