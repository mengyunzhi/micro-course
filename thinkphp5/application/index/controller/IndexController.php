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
            if (request->controller() === 'teacherwx') {
                $url = url('index/login/teacherindex');
                header("Location: $url");
                exit();
            } else {
                $url = url('index/login/index');
                header("Location: $url");
                exit();
            }
            
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