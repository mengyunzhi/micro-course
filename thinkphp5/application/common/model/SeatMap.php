<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class SeatMap extends Model
{
	public function Seat(){

		return $this->hasMany('Seat');
	}
}