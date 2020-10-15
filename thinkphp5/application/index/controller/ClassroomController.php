<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Classroom;
use think\Request;
use think\validate;


class ClassroomController extends Controller
{
	/**
	 * [index description]
	 * @Author   温宇航
	 * @DateTime 2020-10-09T08:27:55+0800
	 * @return   [type]                   [description]
	 */
    public function index()
    {

        $classrooms = Classroom::paginate();
        $this->assign('classrooms', $classrooms);
        return $this->fetch();
        
    }
}