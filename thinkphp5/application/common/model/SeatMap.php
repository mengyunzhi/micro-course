<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class SeatMap extends Model
{
    public static $match = 3;
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

    /**
     * 修改math的值
     * @param $value $match 的值
     */
    public static function setMatch($value)
    {
        self::$match = $value;
    }

    /**
     * 获取match的值
     */
    public static function getMatch()
    {
        return self::$match;
    }
}