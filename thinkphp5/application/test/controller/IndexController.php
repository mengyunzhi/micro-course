<?php
namespace app\test\controller;
use think\Controller;
use app\common\model\Admin;

class IndexController extends Controller
{
    public function index()
    {
        return Admin::encryptPassword('123456');
    }
}