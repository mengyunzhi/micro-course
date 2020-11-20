<?php
namespace app\common\model;
use think\Model;
class Grade extends Model
{
	public function Student()
    {
        return $this->belongsTo('student');
    }
    public function Course()
    {
        return $this->belongsTo('course');
    }
    public function getUsgrade()
	{
		return $this->resigternum/$this->Course->resigternum*100;	
	}
	public function getAllgrade()
	{
		$this->allgrade = $this->getUsgrade()*$this->Course->usmix/100+$this->coursegrade*(100-$this->Course->usmix)/100;
    		return $this->allgrade;
    }
}