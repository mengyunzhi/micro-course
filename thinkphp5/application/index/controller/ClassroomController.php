<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Course;
use app\index\controller\SeatMapController;
use app\index\controller\SeatController;


class ClassroomController extends Controller
{
    public function index()
    {
    	$pageSize = 5;
      $name = Request::instance()->get('name');
      $Classroom = new Classroom;
      $Course = Course::get(3);
      //查询
      if(!empty($name)){
      	$Classroom->where('name','like','%'.$name.'%');
      }
       $classrooms = $Classroom->order('id desc')->paginate($pageSize, false, [
                'query'=>[
                    'name' => $name,
                    ],
                ]);
        $this->assign('classrooms', $classrooms);
        $this->assign('Course', $Course);
        return $this->fetch();
    }
    public function add(){
      return $this->fetch();
    }
     public function edit(){
      return $this->fetch();
    }
}