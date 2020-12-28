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
}