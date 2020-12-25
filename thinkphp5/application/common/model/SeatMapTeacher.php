<?php
namespace app\common\model;
use think\Model;    //  导入think\Model类
use think \ db \ Query;

class SeatMapTeacher extends Model
{
    public function Students()
    {
        return $this->belonsto('student');
    }
}