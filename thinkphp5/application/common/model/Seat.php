<?php
namespace app\common\model;
use think\Model;
use app\common\model\SeatMap;
/**
 * 
 */
class Seat extends Model
{
	public function SeatMap(){

		return $this->hasMany('SeatMap');
	}
	public function getclass($id){
		$seat = Seat::get($id);
		if($seat->state==1) {
			return 'btn btn-success';
		}
		else {
			return 'btn btn-default';
		}
	}
}