<?php
namespace app\index\controller;
use think\Request;

class SeatController 
{
	public function index()
	{
		$id = Request::instance()->get('id');
		dump($id);
		die();


		//实例化seat对象


		//实例化学生对象

		//将seat对象对应数据表中的seated变为1

		//用学号老师导入学生信息进行匹配查找

		//将实例化的seat和学生对象传入V层显示

		//在座位图
	}
}