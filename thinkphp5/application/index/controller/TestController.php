<?php
namespace app\index\controller;

class TestController extends IndexController
{
	public function index()
	{
		$i=1;
		for($i;$i<5;$i++)
		{
			echo "数字为".$i;
		}
		$this->assign('i',$i);
		return $this->fetch();
	}
}
