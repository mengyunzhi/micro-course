<?php
namespace  app\index\controller;
//命名空间，也说明了文件所在的文件夹
use think\Db;//引用数据库操作类
//Index是文件名（名为Index.php），类名
class AdminIndexController{
	public function index()
	{
		var_dump(Db::name('term')->find());
		//获取数据表中第一条数据
	}
}