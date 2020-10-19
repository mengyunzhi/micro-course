<?php
namespace app\common\model;
use think\Model;

class CourseStudent extends Model
{
	public function student()
	{
		return $this->belongsTo('Student');
	}
}