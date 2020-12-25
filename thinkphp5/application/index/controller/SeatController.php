<?php
namespace app\index\controller;
use app\common\model\Seat;
use app\common\model\SeatMap;
use think\Controller;
use think\Request;
use think\validate;
use app\index\controller\SeatMapController;
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
	/**
	* 增加座位
	* @param $seatMapId 对应模板的id
	* @param $url 要跳到的链接
	*/
	public function add($seatMapId, $url) {
		$seatmap = SeatMap::get($seatMapId);

		// 实例化seatmap对象
		for($i = 0; $i < $seatmap->x_map; $i++) {
			for($j = 0; $j < $seatmap->y_map; $j++) {
				$seat = new Seat;
				if(!$this->saveSeat($seatMapId, $seat, $i, $j)) {
					return $this->error('座位保存失败' . $Seat->getError());
				}
			}
		}

		return $this->success('', $url);
	}

	public function saveSeat($seatmap_id,$seat,$i,$j) {
		$seat->x = $i;
		$seat->y = $j;
		$seat->save();
		return 1;
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