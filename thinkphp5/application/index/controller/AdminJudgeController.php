<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;


/**
 * 验证登陆者是否为管理员
 */
class AdminJudgeController extends IndexController {
	public function __construct() {
		//调用父类构造函数
		parent::__construct();
		$Teacher = Teacher::get(['is_admin' => 1]);

		//验证用户是否为管理员
		if(session('teacherId') != $Teacher->id) {
			return $this->error('无此权限', url('Login/index'));
		}
	}	
}