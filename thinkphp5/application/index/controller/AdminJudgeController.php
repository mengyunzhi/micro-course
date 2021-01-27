<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;


/**
 * 验证登陆者是否为管理员
 */
class AdminJudgeController extends IndexController {
    public function __construct()
    {
        //调用父类构造函数
        parent::__construct();

        // 获取当前登录teacherId
        $teacherId = session('teacherId');
        $Teacher = Teacher::get($teacherId);

        //验证用户是否为管理员
        if ($Teacher->is_admin !== 1) {
            return $this->error('无此权限', url('Login/index'));
        }
    }
}