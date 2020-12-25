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
	//0是座位,1是过道
	
}