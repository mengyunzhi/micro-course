<?php
namespace  app\index\controller;
use think\Controller;
use think\Db;//引用数据库操作类
use think\Request;
use app\common\model\Teacher;   // 教师模型
use app\common\model\Admin;
//Index是文件名（名为Index.php），类名
//入口http://127.0.0.1/thinkphp5/public/index.php/index/Admin/index
class AdminController extends Controller
{

	//显示管理员登录表单
	public function index()
	{

		return $this->fetch();
	}
	//正则表达式

	//将管理员登录的表单提交
	 public function login()
    {
    	//接受post信息
    	$postData = Request::instance()->post();
    	var_dump($postData);
        
    	if(Admin::login($postData['username'],$postData['password']))
    	{
    		return $this->success('login success',url('Teacher/index'));
    	}else{
    		//跳转到登录界面。
    		return $this->error('username or password incorrect',url('index'));
    	}
    }
}