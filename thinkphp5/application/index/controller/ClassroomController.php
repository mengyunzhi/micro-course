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
      $this->saveSeatMap($Classroom);
    }

    /**
     * update和save共用的保存教室的方法
     * @param $Classroom 教室对象
     */
    public function saveSeatMap($Classroom) {
      if(!$Classroom->save()) {
        return $this->error('教室未被正确保存');
      }
      if(!$this->saveSeat($Classroom->seat_map_id, $Classroom->id)) {
        return $this->error('教室座位未被正确保存');
      }
      return $this->success('保存成功', url('index'));
    }

    /**
     * 保存教室座位图（利用foreach循环挨个保存教室座位）
     * @param $seatMapId 座位图是id
     * @param $classroomId 教室id
     */
    public function saveSeat($seatMapId, $classroomId) {
      $seatAisles = new SeatAisle;
      $seatAisles = SeatAisle::where('seat_map_id', '=', $seatMapId)->select();
      foreach ($seatAisles as $SeatAisle ) {
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
      $Classroom = Classroom::get($id);
      $seatMaps = SeatMap::all();
      $seats = Seat::where('classroom_id', '=', $id)->select();
      $this->assign('seats', $seats);
      $this->assign('Classroom', $Classroom);
      $this->assign('seatMaps', $seatMaps);
      return $this->fetch();
    }

    /**
     * 更新教室座位图
     */
    public function update() {
      $id = input('post.id');
      $Classroom = Classroom::where('id', '=', $id)->select();
      $Classroom = $Classroom[0];
      $forgettingId = $Classroom->seat_map_id;
      $seatMapId = input('param.seat_map_id');
      $Classroom->name = input('post.name');
      if($forgettingId != $seatMapId) {
        $this->deleteSeat($Classroom->id);
        $Classroom->seat_map_id = input('post.seat_map_id');
        $this->saveSeatMap($Classroom);
      }
      elseif(!$Classroom->save()) {
        return $this->error('座位未被正确更新');
      }
      return $this->success('保存成功', url('index'));
    }

    /**
     * 小部分修改教室座位图，只修改个别座位
     * @param $seatMapId 作为模板的id
     * @param $seats 要修改教室座位图的座位数组
     */
    public function seatMapChange() {
      $id = input('param.id');
      $Classroom = Classroom::get($id);
      $seats = Seat::where('classroom_id', '=', $id)->select();
      $SeatMap = SeatMap::where('id', '=', $Classroom->seat_map_id)->select();
      ksort($seats);
      if(empty($SeatMap)) {
        return $this->error('不存在对应模板');
      }
      $SeatMap = $SeatMap[0];
      $this->assign('seats', $seats);
      $this->assign('Classroom', $Classroom);
      $this->assign('SeatMap', $SeatMap);
      return $this->fetch();
    }

    /**
     * 转化座位为二维数组
     * @param $seats 要展示的座位数组
     * @param $SeatMap 对应的模板
     */
    public function seatDisplay($seats, $SeatMap) {
      $newSeats = [];
      foreach ($seats as $seat)  {
        $newSeats[$seat->x][$seat->y] = $seat;
      } 
      ksort ($newSeats);
      for($i = 0; $i < $SeatMap->x_map; $i++) {
        ksort($newSeats[$i]);
      }
      return $newSeats;
    }

    /**
     * 显示教室的座位图（不是模板）
     */
    public function seating_plan() {
      $id = Request::instance()->param('id');
      $Seat = new Seat;
      $seats = Seat::where('classroom_id', '=', $id)->select();
      $Classroom = Classroom::get($id);
      $SeatMap = new SeatMap;
      $SeatMap = SeatMap::where('id', '=', $Classroom->seat_map_id)->select();
      $SeatMap = $SeatMap[0];
      $newSeats = $this->seatDisplay($seats, $SeatMap);
      if(empty($SeatMap)) {
        return $this->error('本教室座位图还未创建');
      }
      $this->assign('seat', $newSeats);
      $this->assign('Classroom', $Classroom);
      $this->assign('SeatMap', $SeatMap);
      return $this->fetch();
    }

    /**
     * 删除教室
     */
    public function delete() {
      $id = input('param.id');
      $this->deleteSeat($id);
      $Classroom = Classroom::get($id);
      if(!$Classroom->delete()) {
        return $this->error('教室未被正确删除');
      }
      return $this->success('删除成功', url('index'));
    }

    /**
     * 删除教室的座位
     * @param $id 教室id
     */
    public function deleteSeat($id) {
      $Seats = Seat::where('classroom_id', '=', $id)->select();
      foreach ($Seats as $Seat) {
        if(!$Seat->delete()) {
          return $this->error('座位未被正确删除');
        }
      }
      return 1;
    }
    /**
     * 更改座位与过道的函数
     */
    public function isSeat() {
        $id = Request::instance()->param('id/d');
        $classroomId = input('param.classroomId');
        $Seat = new Seat;
        $Seat = Seat::get($id);
        if($Seat->is_seat === "1") {
          $Seat->is_seat = "0";
        } else {
          $Seat->is_seat = "1";
        }
        if(!$Seat->save()) {
          return $this->error('座位保存错误');
        }
        return $this->success('保存成功', url('seatMapChange?id=' . $classroomId));
  }

  /**
   * 思路同上 1为有人，0为无人,默认为0
   */
  public function is_seated(){
    $id = Request::instance()->param('id\d');
    $Seat = new Seat;
    $Seat = Seat::get($id);
    if($Seat->isseated === "1")
    $Seat->isseated = "0";
    else 
    $Seat->isseat = "1";
    $this->save();
  }

  /**
   * 生成二维码
   */
  public function QRCode() {
    $id = input('param.id/d');
    $Classroom = Classroom::get($id);
    $seats = Seat::where('classroom_id', '=', $id)->select();
    $SeatMap = SeatMap::get($Classroom->seat_map_id);
    $seats = $this->seatDisplay($seats, $SeatMap);
    $this->assign('seats', $seats);
    $this->assign('SeatMap', $SeatMap);
    $this->assign('Classroom', $Classroom);
    $url =substr($_SERVER['HTTP_REFERER'], 0, -44);
    $this->assign('url', $url);
    return $this->fetch();
  }
}