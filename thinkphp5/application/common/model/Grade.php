<?php
namespace app\common\model;
use think\Model;
use think\Route;

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
        // 增加判断分母为零的情况
        if ($this->Course->resigternum === 0) {
            $this->usgrade = 0;
        } else {
            $this->usgrade = ceil($this->resigternum / $this->Course->resigternum * 100);
            if ($this->usgrade >= 100) {
                $this->usgrade = 100;
            }
        }

        // 将更改后的记录保存并返回
        $this->save();
        return $this->usgrade;
    }

    /**
     * 对总成绩进行重新计算，并返回总成绩
     */
    public function getAllgrade() {
        $this->allgrade = ceil($this->getUsgrade() * $this->Course->usmix / 100 + $this->coursegrade * (100-$this->Course->usmix) / 100);
        $this->save();
        return $this->allgrade;
    }
}