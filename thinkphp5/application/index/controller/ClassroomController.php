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
class ClassroomController extends AdminJudgeController
{
    public function index()
    {
        /*trace($class1->getMatch());*/
        $pageSize = 5;
        $name = Request::instance()->get('name');
        $Classroom = new Classroom();
        $Classroom->where('is_delete', '<>', 1);

        if (!Teacher::isLogin()) {
              return $this->error('plz login first', url('Login/index'));
        }

        // 查询
        if (!empty($name)) {
              $Classroom->where('name', 'like', '%' . $name . '%');
        }
        $classrooms = $Classroom->order('id desc')->paginate($pageSize, false, [
                'query' => [
                    'name' => $name,
                    ],
                ]);
        $this->assign('classrooms', $classrooms);
        $class2 = new SeatMap();
        trace($class2->getMatch());
        return $this->fetch();
    }

    /**
     * 增加教室
     */
    public function add()
    {
        //判断模板是否存在
        if (empty(SeatMap::all())) {
            return $this->error('当前不存在座位图模板，无法添加教室', url('index'));
        }
        $seatMapId = Request::instance()->param('seatMapId');
        $classroomName = input('param.classroomName');
        $seatMap = SeatMap::all();
        $Classroom = new Classroom();

        // 为对象属性赋值
        $Classroom->id = 0;
        $Classroom->name = '';
        $Classroom->seat_map_id = 0;

        // 如果存在上级路由（模板选择一级）传过来的值，则赋值
        if (!is_null($seatMapId)) {
            $Classroom->seat_map_id = $seatMapId;
        }
        if (!is_null($classroomName)) {
            $Classroom->name = $classroomName;
        }

        // 向v层传值
        $this->assign('Classroom', $Classroom);
        $this->assign('seatMaps', $seatMap);
        return $this->fetch('edit');
    }

    /**
     * 保存教室
     */
    public function save()
    {
        // 新建教室信息，并进行保存
        $Classroom = new Classroom();
        $Classroom->name = input('post.name');
        $Classroom->seat_map_id = input('post.seat_map_id');
        $Classroom->create_time = time();
        $Classroom->update_time = time();
        $Classroom->is_delete = 0;

        // 下面为上课部分的初始化
        // 新建后，默认上课课程id为0，表示没有处于上课状态
        $Classroom->course_id = 0;
        $Classroom->begin_time = 0;
        $Classroom->out_time = 0;
        $Classroom->sign_deadline_time = 0;
        $Classroom->sign_begin_time = 0;
        $Classroom->sign_time = 0;
        $this->saveSeatMap($Classroom);
    }

    /**
     * update和save共用的保存教室的方法
     * @param $Classroom 教室对象
     */
    public function saveSeatMap($Classroom)
    {
        // 将教室保存，并判断保存是否成功
        if (!$Classroom->save()) {
            return $this->error('教室未被正确保存');
        }
        if (!$this->saveSeat($Classroom->seat_map_id, $Classroom->id)) {
            return $this->error('教室座位未被正确保存');
        }
        return $this->success('保存成功', url('index'));
    }

    /**
     * 保存教室座位图（利用foreach循环挨个保存教室座位）
     * @param $seatMapId 座位图是id
     * @param $classroomId 教室id
     */
    public function saveSeat($seatMapId, $classroomId)
    {
      // 通过座位图id获取对应的座位模板的id，从而获得与座位模板对应的座位
        $seatAisles = new SeatAisle();
        $seatAisles = SeatAisle::where('seat_map_id', '=', $seatMapId)->select();
        foreach ($seatAisles as $SeatAisle) {
            $Seat = new Seat();
            $Seat->x = $SeatAisle->x;
            $Seat->y = $SeatAisle->y;
            $Seat->is_seat = $SeatAisle->is_seat;
            $Seat->classroom_id = $classroomId;
            if (!$Seat->save()) {
                return error('座位' . $Seat->id . '未被正确保存');
            }
        }
        // 返回1，用于判断是否保存成功
        return 1;
    }

    /**
     * 教室编辑
     * 座位图修改：若只是修改部分是否为过道/座位
     * 则直接调用seatmap_change方法修改，否则修改对应模板号，若修改模板号则需要将之前教室对应的座位删除掉
     */
    public function edit()
    {
            // 获取教室相关信息，并将教室id实例化
        $classroomId = Request::instance()->param('classroomId/d');
        $Classroom = Classroom::get($classroomId);
        $seatMapId = Request::instance()->param('seatMapId');
        $SeatMapController = new SeatMapController();
        if ($SeatMapController->judgeClassroom(0, $Classroom->id)) {
        //如果存在上级路由（模板选择一级）传过来的值，则赋值
            if (!is_null($seatMapId)) {
                $Classroom->seat_map_id = $seatMapId;
            }

        //座位实例化
        $seatMaps = SeatMap::all();
        $seats = Seat::where('classroom_id', '=', $classroomId)->select();

        //向V层传值
        $this->assign('seats', $seats);
        $this->assign('Classroom', $Classroom);
        $this->assign('seatMaps', $seatMaps);

        //返回v层渲染
        return $this->fetch();
        }
    }
      
    /**
     * 更新教室座位图
     */
    public function update()
    {
        // 获取教室的id，并对教室进行实例化
        $classroomId = input('post.id');
        $Classroom = Classroom::get($classroomId);
        $seatChanged = 0;

        // 获取教室对象对应的座位图模板
        $forgettingId = $Classroom->seat_map_id;
        $seatMapId = input('param.seat_map_id');
        $Classroom->name = input('post.name');
        if ($forgettingId != $seatMapId) {
            $this->deleteSeat($Classroom->id);
            $Classroom->seat_map_id = input('post.seat_map_id');
            $this->saveSeatMap($Classroom);
            $seatChanged = 1;
        }
        $Classroom->save();
        $Classroom1 = Classroom::get($classroomId);
        if ($Classroom->name != $Classroom1->name || $Classroom->seat_map_id != $Classroom1->seat_map_id) {
            return $this->error('座位未被正确更新');
        }
        return $this->success('保存成功', url('index'));
    }

    /**
     * 小部分修改教室座位图，只修改个别座位
     * @param $seatMapId 作为模板的id
     * @param $seats 要修改教室座位图的座位数组
     */
    public function seatMapChange()
    {
        // 获取教室id,并实例化教室对象
        $classroomId = input('param.id');
        $Classroom = Classroom::get($classroomId);
        $SeatMapController = new SeatMapController();
        if ($SeatMapController->judgeClassroom(0, $Classroom->id)) {
            // 根据教室id获取该教室的所有座位，并获取该教室对应的座位模板
            $seats = Seat::where('classroom_id', '=', $classroomId)->select();
            $SeatMap = SeatMap::get($Classroom->seat_map_id);
            if (is_null($SeatMap)) {
                return $this->error('不存在对应模板');
            }
            $seats = $this->seatDisplay($seats, $SeatMap);
            $this->assign('seats', $seats);
            $this->assign('Classroom', $Classroom);
            $this->assign('SeatMap', $SeatMap);
            return $this->fetch();
        }
    }

    /**
     * 转化座位为二维数组
     * @param $seats 要展示的座位数组
     * @param $SeatMap 对应的模板
     */
    public function seatDisplay($seats, $SeatMap)
    {
        // 定义一个数组，将座位转换为二维数组按先x后y的方式排好序
        $newSeats = [];
        foreach ($seats as $seat) {
            $newSeats[$seat->x][$seat->y] = $seat;
        }
        ksort($newSeats);
        for ($i = 0; $i < $SeatMap->x_map; $i++) {
            ksort($newSeats[$i]);
        }
        return $newSeats;
    }

    /**
     * 显示教室的座位图（不是模板）
     */
    public function seatingPlan()
    {
        // 接收教室id，并根据教室id查找出该教室的所有座位，同时对教室进行实例化
        $classroomId = Request::instance()->param('id');
        $seats = Seat::where('classroom_id', '=', $classroomId)->select();
        if (empty($seats)) {
            return $this->error('此教室无座位图', url('index'));
        }
        $Classroom = Classroom::get($classroomId);

        // 通过教室id获取对应的教室模板，并判断是否存在
        $SeatMap = SeatMap::get($Classroom->seat_map_id);
        if (empty($SeatMap)) {
            return $this->error('本教室座位图还未创建');
        }
        // 将座位转换为二维数组，并按照先x后y进行排序
        $newSeats = $this->seatDisplay($seats, $SeatMap);

        $this->assign('seat', $newSeats);
        $this->assign('Classroom', $Classroom);
        $this->assign('SeatMap', $SeatMap);
        return $this->fetch();
    }

    /**
     * 删除教室
     */
    public function delete()
    {
        // 获取教室id，并进行实例化
        $classroomId = input('param.id');
        $Classroom = Classroom::get($classroomId);
        $SeatMapController = new SeatMapController();
        if ($SeatMapController->judgeClassroom(0, $Classroom->id)) {
            $Classroom->is_delete = 1;
            $this->deleteSeat($classroomId);

              // 删除教室
            if (!$Classroom->save()) {
                return $this->error('教室未被正确删除');
            }
            return $this->success('删除成功', url('index'));
        }
    }

    /**
     * 删除教室的座位
     * @param $id 教室id
     */
    public function deleteSeat($id)
    {
        $seats = Seat::where('classroom_id', '=', $id)->select();
        if (!empty($seats)) {
            foreach ($seats as $Seat) {
                if (!$Seat->delete()) {
                    return $this->error('座位未被正确删除');
                }
            }
        }
        return 1;
    }

    /**
     * 更改座位与过道的函数
     */
    public function isSeat()
    {
        // 获取座位id和教室id，并对座位进行实例化
        $seatId = Request::instance()->param('id/d');
        $classroomId = input('param.classroomId');
        $SeatMapController = new SeatMapController();
        $url = url('seatMapChange?id=' . $classroomId);
        $SeatMapController->seatState($seatId, 1, $url);
  }

    /**
     * 思路同上 1为有人，0为无人,默认为0，名字最好不要是is_seated,因为它的功能主要是changeState
     */
    public function isSeated()
    {
        // 获取座位id，并对座位进行实例化
        $seatId = Request::instance()->param('id/d');
        $Seat = Seat::get($seatId);

        // 判断座位是否被坐，并将其改为相反状态
        if ($Seat->isseated === 1) {
            $Seat->isseated = 0;
        } else {
            $Seat->isseat = 1;
        }
        return $Seat->save();
    }

    /**
     * 生成二维码
     */
    public function QRCode()
    {
        $id = input('param.id/d');
        $Classroom = Classroom::get($id);
        $seats = Seat::where('classroom_id', '=', $id)->order('id desc')->select();
        if (empty($seats)) {
            return $this->error('当前教室无座位', url('index'));
        }
        $SeatMap = SeatMap::get($Classroom->seat_map_id);
        $this->assign('seats', $seats);
        $this->assign('SeatMap', $SeatMap);
        $this->assign('Classroom', $Classroom);
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/index/login/studentWx?seatId=';
        $urlTeacher = 'http://' . $_SERVER['HTTP_HOST'] . '/index/login/teacherIndex?classroomId=' . $Classroom->id;
        $this->assign('url', $url);
        $this->assign('urlTeacher', $urlTeacher);
        return $this->fetch();
    }

    /**
     * 保存成功
     */
    public function successChanging()
    {
        return $this->success('操作成功', url('index'));
    }

    /**
     * 回跳到seatingPlan
     */
    public function submit()
    {
        return $this->success('操作成功', url('seatingPlan?id=' . input('id')));
    }
}