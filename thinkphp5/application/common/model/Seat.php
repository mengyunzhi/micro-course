<?php
namespace app\common\model;
use think\Model;
use app\common\model\SeatMap;
use think\Request;

class Seat extends Model
{
  public function student()
  {
    return $this->belongsto('student');
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