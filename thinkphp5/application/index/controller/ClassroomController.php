<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Seat;
use app\common\model\SeatAisle;
use app\common\model\Course;
use app\index\controller\SeatMapController;
use app\index\controller\SeatController;
use app\common\model\Teacher;

/**
 * 教室管理
 */
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

    /**
     * 增加教室
     */
    public function add() {
      $seatMap = SeatMap::all();
      $this->assign('seatMaps', $seatMap);
      return $this->fetch();
    }

    /**
     * 保存教室
     */
    public function save() {
      $Classroom = new Classroom;
      $Classroom->name = input('post.name');
      $Classroom->seat_map_id = input('post.seat_map_id');
      if(!$Classroom->save()) {
        return $this->error('教室未被正确保存');
      }
      if(!$this->saveSeat($Classroom->seat_map_id, $Classroom->id)) {
        return $this->error('教师座位未被正确保存');
      }
      return $this->success('保存成功', url('index'));
    }

    /**
     * 保存教室座位图（挨个保存教室座位）
     */
    public function saveSeat($seatMapId, $classroomId) {
      $seatAisles = new SeatAisle;
      $seatAisles = SeatAisle::where('seat_map_id', '=', $seatMapId)->select();
      foreach ($seatAisles as $seatAisle ) {
        $Seat = new Seat;
        $Seat->x = $SeatAisle->x;
        $Seat->y = $SeatAisle->y;
        $Seat->is_seat = $SeatAisle->state;
        $Seat->classroom_id = $classroomId;
        if(!$Seat->save()) {
          return error('座位' . $Seat->id . '未被正确保存');
        }
      }
      return 1;
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
     * 小部分修改教室座位图，只修改个别座位
     */
    public function seatmap_change(){

      return $this->fetch();
    }

    /**
     * 显示教室的座位图（不是模板）
     */
    public function seating_plan(){
      $id = Request::instance()->param('id');
      $Seat = new Seat;
      $Seat = Seat::where('classroom_id', '=', $id)->select();
      $Classroom = Classroom::get($id);
      $SeatMap = new SeatMap;
      $SeatMap = SeatMap::where('id', '=', $Classroom->seat_map_id)->select();
      if(empty($SeatMap)) {
        return $this->error('本教室座位图还未创建');
      }
      $this->assign('Seat', $Seat);
      $this->assign('Classroom', $Classroom);
      $this->assign('SeatMap', $SeatMap);
      return $this->fetch();
    }

    /**
     * 删除教室
     */
    public function delete() {

    }

    /**
     * 删除教室的座位
     */
    public function deleteSeat() {
      
    }
}