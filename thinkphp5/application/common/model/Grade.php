<?php
namespace app\common\model;
use think\Model;

/**
 * 成绩对应的类，负责成绩的保存和计算
 */
class Grade extends Model {
    /**
     * 通过外键student_id获取对应的学生对象
     */
	public function Student() {
        return $this->belongsTo('student');
    }

    /**
     * 通过course_id获取对应的课程对象
     */
    public function Course() {
        return $this->belongsTo('course');
    }

    /**
     * 对考勤成绩进行重新计算，并返回当前考勤成绩
     */
    public function getUsgrade() {
        $this->usgrade = $this->resigternum / $this->Course->resigternum * 100;
        // 将更改后的记录保存并返回
        $this->save();
		return $this->usgrade;	
	}

    /**
     * 对总成绩进行重新计算，并返回总成绩
     */
	public function getAllgrade() {
		$this->allgrade = $this->getUsgrade() * $this->Course->usmix / 100 + $this->coursegrade * (100-$this->Course->usmix) / 100;
        $this->save();
		return $this->allgrade;
    }
}