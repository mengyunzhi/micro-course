<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Classroom;
use think\Request;
use think\validate;


class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::paginate();
        $this->assign('classrooms', $classrooms);
        return $this->fetch();
    }
}