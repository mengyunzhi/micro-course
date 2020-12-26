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
use app\common\model\Teacher;

class ClassroomController extends Controller
{
    public function index()
    {
    	$pageSize = 5;
      $name = Request::instance()->get('name');
      $Classroom = new Classroom;

      if(!Teacher::isLogin())
        {
            return $this->error('plz login first',url('Login/index'));
        }


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

    /**
     * 教室编辑
     * 座位图修改：若只是修改部分是否为过道/座位
     * 则直接调用seatmap_change方法修改，否则修改对应模板号，若修改模板号则需要将之前教室对应的座位删除掉
     */
     public function edit() {
      $id = Request::instance()->param('id/d');
      $classroom = Classroom::get($id);
      $this->assign('classroom', $classroom);
      return $this->fetch();
    }

    /**
     * 小部分修改教室座位图
     */
    public function seatmap_change(){

      return $this->fetch();
    }
    public function seating_plan(){
      return $this->fetch();
    }
}