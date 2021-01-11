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
		$Teacher = Teacher::get(['username' => 'admin']);

		//验证用户是否为管理员
		if(session('teacherId') != $Teacher->id) {
			return $this->error('plz login first',url('Login/index'));
		}
	}	
}