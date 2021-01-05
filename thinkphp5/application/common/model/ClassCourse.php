<?php
namespace app\common\model;
use think\Model;
use think\Request;//内置请求类

/**
 * 上课课程类，用于统计上课课程的信息的和数据的保存
 */
class ClassCourse extends Model {
	/**
	* 获取教室对象
	*/
	public function classroom() {
		return $this->belongsto('classroom');
	}

	/**
	* 获取教室对象
	*/
	public function course() {
		return $this->belongsto('course');
	}
}