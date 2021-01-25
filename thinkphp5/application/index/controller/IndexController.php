<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;
use app\common\model\Menu;
use app\common\view\MenuView;

/**
 *  
 */
class IndexController extends Controller { 
    public function __construct()
    {
        // 调用父类构造函数
        parent::__construct();
        // 验证用户是否登录
        if(!Teacher::isLogin()) {
            // 首先判断请求的控制器是不是微信端，如果是微信端，则跳转到微信登陆界面
            if (request()->controller() === 'teacherwx') {
                $url = url('index/login/teacherindex');
                header("Location: $url");
                exit();
            // 否则跳转到web端登录界面
            } else {
                $url = url('index/login/index');
                header("Location: $url");
                exit();
            }
        // 如果已经登陆，跳转到相应的首页
        }
        $this->assign('menuView', new menuView());
    }

    /**
     * 负责跳转到登陆界面
     */
    public function index() {
        $url = url('index/login/index');
        header("Location: $url");
        exit();
    }   
}