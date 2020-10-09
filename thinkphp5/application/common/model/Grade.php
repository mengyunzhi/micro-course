<?php
namespace app\common\model;
use think\Model;
class Grade extends Model
{
	private $Student;
public function Student()
    {
        return $this->belongsTo('student');
    }
    public function Course()
    {
        return $this->belongsTo('course');
    }
}