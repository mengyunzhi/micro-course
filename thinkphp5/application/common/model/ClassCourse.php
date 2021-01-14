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
	/**
     * 获取要显示的创建时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
}