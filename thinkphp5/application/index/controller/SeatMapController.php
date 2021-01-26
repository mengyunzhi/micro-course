<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Course;
use app\common\model\Grade;
use app\common\model\SeatAisle;
use app\common\model\Teacher;
use app\common\model\Seat;

/**
 * 座位图模板
 */
class SeatMapController extends Controller {
    public function index()
    {
        $name = input('param.name');
        $seatMaps = new SeatMap();
        if (!is_null($name)) {
            $seatMaps = SeatMap::where('name', 'like', '%' . $name . '%');
        }
        $seatMaps = $seatMaps->order('id desc')->paginate(5);
        $this->assign('seatMaps', $seatMaps);
        return $this->fetch();
    }

    /**
     * 座位图模板显示
     */
    public function template()
    {
        // 判断有无模板
        if (empty(SeatMap::all())) {
            return $this->error('当前不存在座位图模板', $_SERVER["HTTP_REFERER"]);
        }


        $match = input('param.match');
        $url = '';
        //判断上一级路由是否是教室那边
        //字符串的模式匹配
        if (preg_match("/add/i", $_SERVER["HTTP_REFERER"]) || !empty($match)) {
            $url = 'classroom/add';
            $match = 1;
        } elseif (preg_match("/edit/i", $_SERVER["HTTP_REFERER"]) || !empty($match)) {
            $url = 'classroom/edit';
            $match = 1;
        }
        $classroomId = Request::instance()->param('classroomId');
        $classroomName = Request::instance()->param('classroomName');
        $seatMapAsc = SeatMap::order('id')->select();
        $id = Request::instance()->param('id/d');
        $course_id = Request::instance()->param('course_id');
        // 实例化课程
        $Course = Course::get($course_id);
        $SeatMap = SeatMap::get($id);
        $seatAisles = new SeatAisle();
        $seatAisles = SeatAisle::order('id asc')->where('seat_map_id', '=', $id)->select();

        if (empty($seatAisles)) {
            return $this->error('模板' . $SeatMap->name . '座位图为空，请重新编辑或删除该模板', url('index'));
        }
        $index = input('index') === '' ? 0 : input('index');
        $count = sizeof($seatMapAsc);

        $this->assign('index', $index);
        $this->assign('count', $count);
        $this->assign('match', $match);
        $this->assign('classroomName', $classroomName);
        $this->assign('classroomId', $classroomId);
        $this->assign('url', $url);
        $this->assign('seatMaps', $seatMapAsc);
        $this->assign('SeatMap', $SeatMap);
        $this->assign('seatAisles', $seatAisles);
        $this->assign('Course', $Course);
        return $this->fetch();
    }

    /**
     * 测试
     */
    public function test()
    {
        $arry = [];
        for ($i = 0; $i < 4; $i++) {
            $arry[$i] = $i;
        }
        dump(sizeof($arry));
        for ($i = 0; $arry[$i] !== 4; $i++) {
            dump($i);
            dump($arry[$i]);
        }
        dump($arry[$i]);
    }

    /**
     * 返回座位图模板数据库里面id升序的数组
     */
    public function asc()
    {
        return SeatMap::order('id')->select();
    }
    /**
     * 返回座位图模板数据库id降序的数组
     * @param $arry 要逆置的数组
     */
    public function desc($arry)
    {
        return array_reverse($arry);
    }

    /**
     * 增加和编辑模板
     */
    public function add()
    {
        $id = input('id');

        // 默认为新增
        $SeatMap = new SeatMap();
        $SeatMap->id = 0;
        $SeatMap->is_first = 0;
        $SeatMap->name = '';
        $SeatMap->x_map = '';
        $SeatMap->y_map = '';

        // 如果是编辑或者重置
        if (!is_null($id)) {
            $SeatMap = SeatMap::get(['id' => $id]);
        }
        $this->assign('SeatMap', $SeatMap);
        return $this->fetch();
    }

    /**
     * 编辑模板座位图的过道以及座位
     * 设置每个座位是过道还是座位
     */
    public function edit()
    {
        $id = Request::instance()->param('id/d');

        //判断当前有无使用此模板的教室上课
        if ($this->judgeClassroom($id, 0)) {
            $seatAisle = SeatAisle::where('seat_map_id', '=', $id)->select();
            rsort($seatAisle);
            $seatAisle = array_reverse($seatAisle);
            $this->assign('seatAisles', $seatAisle);
            $SeatMap = new SeatMap();
            $SeatMap = SeatMap::get($id);
            $this->assign('SeatMap', $SeatMap);
            return $this->fetch();
        }
    }

    /**
     * 修改对应被修改模板的教室
     */
    public function editClassroom()
    {
        $seatMapId = input('seatMapId');
        $classrooms = Classroom::where('seat_map_id', '=', $seatMapId)->select();
        if (!empty($classrooms)) {
            foreach ($classrooms as $Classroom) {
                $ClassroomController = new ClassroomController();
                $ClassroomController->deleteSeat($Classroom->id);
                $ClassroomController->saveSeat($seatMapId, $Classroom->id);
            }
        }
        return $this->success('操作成功', url('index'));
    }

    /**
     * 保存模板的行和列
     * 通过模板名字来判断是不是已经添加了模板（解决添加模板时行列输入错误的问题，同时也起到了编辑的作用）
     */
    public function save()
    {
        // 编辑或者模板重置
        if (input('id') != '0') {
            $SeatMap = SeatMap::get(['id' => input('id')]);
            $this->deleteSeatAisle(input('id'));
            $SeatMap->delete();

            // 重设或编辑的模板和之前的模板id必须一样
            $SeatMap = new SeatMap();
            $SeatMap->id = input('id');

            // 模板的第一性不能改变
            $SeatMap->is_first = input('isFirst');
        } else {
                    // 新增
                    $SeatMap = new SeatMap();

                    //首个座位图是第一个
                    $seatMaps = SeatMap::all();
            if (empty($seatMaps)) {
                        $SeatMap->is_first = 1;
            }
        }

        // 赋值
        $SeatMap->name = input('name');
        $SeatMap->x_map = Request::instance()->post('xMap');
        $SeatMap->y_map = Request::instance()->post('yMap');

        // 保存
        if (!$SeatMap->save()) {
            return $this->error('保存信息错误' . $SeatMap->getError());
        }

        // 新增的模板id为字符串0
        $id = $SeatMap->id;
        if (input('id') === '0') {
            $seatMaps = SeatMap::all();
            foreach ($seatMaps as $seatMap) {
                if ($seatMap->id != $id) {
                    $seatMap->is_last = 0;
                    $seatMap->save();
                }
            }
        }
        $this->addseatAisle($id, url('edit?id=' . $id));
    }

    /**
     * 模板重置
     */

    public function seatMapReset()
    {
        $isFirst = SeatMap::get(['id' => input('id')])->is_first;
        $id = input('id');
        return $this->success('请重新填写模板信息', url('add?id=' . $id . '&isFirst=' . $isFirst));
    }

    /**
     * 利用双重for循环挨个存储座位模板的座位
     */
    public function addseatAisle($seatMapId, $url)
    {
        $seatmap = SeatMap::get($seatMapId);

        // 建立模板座位图
        for ($i = 0; $i < $seatmap->x_map; $i++) {
            for ($j = 0; $j < $seatmap->y_map; $j++) {
                $seatAisle = new seatAisle();
                if (!$this->saveSeatAisle($seatMapId, $seatAisle, $i, $j)) {
                    return $this->error('座位保存失败' . $seatAisle->getError());
                }
            }
        }
        return $this->success('请选择过道', $url);
    }

    /**
     * 保存单个座位
     * @param $seatMapId 对应模板的id
     * @param $seatAiale 座位
     * @param $url 要跳到的链接
     * @param $i 行
     * @param $j 列
     */
    public function saveSeatAisle($seatMapId, $seatAisle, $i, $j)
    {
        $seatAisle->x = $i;
        $seatAisle->y = $j;
        $seatAisle->seat_map_id = $seatMapId;
        $seatAisle->create_time = time();
        $seatAisle->update_time = time();
        return $seatAisle->save();
    }

    /**
     * 过道和座位的切换
     * 默认为座位，即is_seat = 0;
     * 过道is_seat = 1
     */
    public function isSeat()
    {
        $id = Request::instance()->param('id/d');
        //目的链接
        $url = url('index/SeatMap/edit?id=' . SeatAisle::get($id)->seat_map_id);

        $this->seatState($id, 0, $url);
    }

    /**
     * 修改座位与过道状态
     * @param $seatId 对应座位或者座位图座位的ID
     * @param $SOA 是教室的座位还是座位图的座位
     * @param $url 跳转链接
     */
    public function seatState($seatId, $SOA, $url)
    {
        if (!is_null($seatId)) {
            // 1代表是教室座位状态修改
            if ($SOA === 1) {
                $SeatAisle = Seat::get($seatId);
            } else {
                // 0代表座位图状态修改
                $SeatAisle = SeatAisle::get($seatId);
            }
        } else {
            return $this->error('未找到相关作为信息');
        }

        // 如果是座位则切换为过道
        if ($SeatAisle->is_seat === 1) {
            $SeatAisle->is_seat = 0;
        } else {
            $SeatAisle->is_seat = 1;
        }
        if (!$SeatAisle->save()) {
            $this->error('系统未找到ID为' . $id . '的记录');
        }
        // 如果设置成功返回设置成功的
        header("Location: $url");
        exit();
    }

    /**
     * 删除座位模板
     */
    public function delete()
    {
        $id = Request::instance()->param('id');
        $seatMap = SeatMap::get($id);

        // 判断当前被删的模板的教室是否正在上课
        if ($this->judgeClassroom($id, 0)) {
            //删除对应教师
            if (!$this->deleteClassrooms($seatMap->id)) {
                    return $this->error('删除模板对应的教室失败', url('index'));
            }
             //判断对应座位是否被删除
            if ($this->deleteSeatAisle($id)) {
                $seats = $this->asc();
                $seatMaps = SeatMap::all();
                if (sizeof($seatMaps) != '1') {
                    // 如果是最后一个则其前一个座位变为最后一个
                    if ($seatMap->is_last === 1) {
                        $SeatMap = array_reverse($seats);

                        $SeatMap[1]->is_last = 1;
                        $SeatMap[1]->save();
                    }

                    // 如果是第一个则其后一个座位变为第一个
                    if ($seatMap->is_first === 1) {
                        $SeatMap = $seats;
                        $SeatMap[1]->is_first = 1;
                        $SeatMap[1]->save();
                    }
                }
                if ($seatMap->delete()) {
                    return $this->success('删除成功', url('index'));
                }
            }
        }
    }

    /**
     * 删除模板对应的教室
     * @param $seatMapId
    */
    public function deleteClassrooms($seatMapId)
    {
        $classrooms = Classroom::where('seat_map_id', '=', $seatMapId)->select();
        if (!empty($classrooms)) {
            foreach ($classrooms as $Classroom) {
                $ClassroomController = new ClassroomController();
                $Classroom->is_delete = 1;
                if (!$ClassroomController->deleteSeat($Classroom->id) || !$Classroom->save()) {
                    return flase;
                }
            }
        }
        return true;
    }

    /**
     * 挨个删除座位
     */
    public function deleteSeatAisle($id)
    {
        $seatAisles = SeatAisle::where('seat_map_id', '=', $id)->select();
        foreach ($seatAisles as $SeatAisle) {
            if (!$SeatAisle->delete()) {
                return $this->error('模板座位未被删除', url('index'));
            }
        }
        return true;
    }

    /**
     * 判断对应当前模板的教室是否在上课
     * @param $seatMapId 对应模板ID
     * @param $classroomId 想要删除的classroom的id
     */
    public function judgeClassroom($seatMapId, $classroomId)
    {
        $teachers = Teacher::all();
        foreach ($teachers as $Teacher) {
            if ($Teacher->classroom_id !== 0 && !is_null($Teacher->classroom_id)) {
                $Classroom = Classroom::get($Teacher->classroom_id);
                if (!is_null($Classroom)) {
                    if ($Classroom->id === $classroomId) {
                        if ($Classroom->out_time < time()) {
                            return $this->error('当前此教室正在上课，请稍后删除', url('classroom/index'));
                        } else {
                            return true;
                        }
                    }
                    if ($Classroom->seat_map_id === $seatMapId) {
                        if ($Classroom->out_time < time()) {
                            return $this->error('当前有与此模板对应的教室正在上课，请稍后删除', url('index'));
                        } else {
                            return true;
                        }
                    }
                }
            }
        }
        return true;
    }
}