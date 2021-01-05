<?php
namespace app\common\model;
use think\Model;
use think\Request;//内置请求类

/**
 * 上课缓存类，用于统计上课情况和数据保存
 */
class ClassDetail extends Model
{
	/**
	* 获取学生对象
	*/
	public function student() {
		return $this->belongsto('student');
	}

	/**
	* 获取上课课程缓存对象
	*/
	public function classCourse() {
		return $this->belongsto('classCourse');
	}

	/**
	* 获取教室信息
	*/
	public function classroom() {
		return $this->belongsto('classroom');
	}

	/**
	* 获取课程信息，对应该条缓存的课程信息
	*/
	public function course() {
		return $this->belongsto('course');
	}
}