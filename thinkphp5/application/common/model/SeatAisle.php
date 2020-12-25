<?php
namespace app\common\model;
use think\Model;
/**
 * 
 */
class SeatAisle extends Model
{
	public function getclass($id){
		$SeatAisle = SeatAisle::get($id);
		if($SeatAisle->state==0) {
			return 'btn btn-success';
		}else {
			return 'btn btn-default';
		}
	}
}