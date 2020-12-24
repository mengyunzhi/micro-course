<?php
namespace app\common\model;
use think\Model;
use think\Request;

class Seat extends Model
{
    public function student()
    {
        return $this->belongsto('student');
    }
}