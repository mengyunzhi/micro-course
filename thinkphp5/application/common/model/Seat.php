<?php
namespace app\common\model;
use think\Model;
use app\common\model\SeatMap;
use think\Request;

class Seat extends Model
{
  public function student()
  {
    return $this->belongsto('student');
  }
	public function SeatMap(){
		return $this->hasMany('SeatMap');
	}
	//0是座位,1是过道
	
}