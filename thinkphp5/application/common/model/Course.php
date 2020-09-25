<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Student 学生表
 */
class Course extends Model
{
    public function students()
    {
        return $this->belongsToMany('Student',  config('database.prefix') . 'student');
    }
}