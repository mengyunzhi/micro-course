<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;

/**
 *  
 */
class IndexController extends Controller
{
	
	public function __construct()
	{
		# code...

		//调用父类构造函数
		parent::__construct();
		//验证用户是否登录
		if(!Teacher::isLogin())
		{
			return $this->error('plz login first',url('Login/index'));
		}
	}

	public function index()
	{

	}
}