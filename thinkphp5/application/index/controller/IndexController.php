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
        //调用父类构造函数
        parent::__construct();
        //验证用户是否登录
        if(!Teacher::isLogin()) {
            $url = url('index/login/index');
            header("Location: $url");
            exit();
        }
        
        $this->assign('menuView', new menuView());
    }

    public function index()
    {
        $url = url('index/login/index');
        header("Location: $url");
        exit();
    }
}