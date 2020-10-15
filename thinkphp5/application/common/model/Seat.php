<?php 
namespace app\common\model;
use think\Model;

/**
 * Seat座位
 */
/**
 * 
 */
class Seat extends Model
{
	public function isCurrent()
	{
		return $this->seated ===1;
	}

}