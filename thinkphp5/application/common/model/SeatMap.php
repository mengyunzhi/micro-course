<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class SeatMap extends Model
{
	public function Seat(){

		return $this->hasMany('Seat');
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
    /**
     * 获取要显示的更新时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getUpdateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
}