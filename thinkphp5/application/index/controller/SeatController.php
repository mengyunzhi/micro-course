<?php
namespace app\index\controller;
use app\common\model\Seat;
use app\common\model\SeatMap;
use think\Controller;
use think\Request;
use think\validate;
use app\common\model\SeatMapController;
use app\common\model\Classroom;
use app\common\model\Grade;
use app\common\model\ClassCourse;
use app\common\model\ClassDetail;
use app\common\model\Student;


/**
 * 座位管理，负责座位的信息更新和信息重置等
 */
class SeatController extends controller {
    /**
    * 设置座位,当前状态是座位则编程过道，否则为座位.0为座位，1为过道，默认为0
    */
    public function is_seat() {
        // 接收座位id，实例化请求
        $Request = Request::instance();
        $seatId = Request::instance()->param('id\d');
        $Seat = Seat::get($seatId);

        // 判断座位状态(座位为1，过道为0)，并进行修改
        if($Seat->state === 1) {
            $Seat->state = 0;
        } else {
            $Seat->state = 1;
        }
        
        // 将修改后的结果保存并判断
        if (!$Seat->save()) {
            return $this->error('座位状态更改失败', $Request->header('referer'));
        }
    }

    /**
     * 思路同上，设置有无人做
     */
    public function is_seated() {
        // 获取座位id，并将座位进行实例化
        $Request = Request::instance();
        $seatId = Request::instance()->param('id\d');
        $Seat = Seat::get($seatId);

        // 判断座位是否被坐，并更改状态
        if($Seat->isseated === 1) {
            $Seat->isseated = 0;
        } else { 
            $Seat->isseat = 1;
        }

        // 增加判断是否保存成功
        if (!$Seat->save()) {
            return $this->error('是否被坐状态更改失败', $Request = Request::instance());
        }
    }

    /**
     * 上课签到、将上课座位属性student_id变为其id
     */
    public function sign() {
        // 首先根据微信端的Cookie值判断是否该该学生信息，并获取该学生的id信息 
        $studentId = Request::instance()->param('studentId/d');

        // 获取学生id和教室座位id,并实例化教室座位对象
        $seatId = Request::instance()->param('seatId');
        if (is_null($Seat = Seat::get($seatId)) || is_null($seatId)) {
            return $this->error('座位获取失败，请重新签到扫码', Request::instance()->header('referer'));
        }
        $Classroom = Classroom::get($Seat->classroom_id);
        $classDetail = new ClassDetail();

        // 判断当前座位是否有学生，并判断第二个扫码的人跟第一个人是否是一个人
        if ($Seat->student_id !== $studentId && $Seat->student_id !==0 && !is_null($Seat->student_id)) {
            return $this->error(
                '当前已被' . $Seat->student->name . '绑定，请选择其他座位重新扫码',
                url('Student/aftersign?studentId' . $studentId . '&courseId=' . $Classroom->course_id)
            );
        }

        // 为新建和更新上课详情做准备(获取上课课程对象)
        $isUpdate = false;
        $classCourse = ClassCourse::get(['classroom_id' => $Seat->classroom_id, 'begin_time' => $Classroom->begin_time]);

        // 增加判断是否为已经扫码，更改座位情况
        $SeatFirst = Seat::get(['student_id' => $studentId, 'classroom_id' => $Seat->classroom_id]);
        if (!is_null($SeatFirst)) {
            $SeatFirst->student_id = null;
            $SeatFirst->is_seated = 0;
            if (!$SeatFirst->save()) {
                return $this->error('座位信息更新失败', request()->header('referer'));
            }
            // 获取对应的上课详情对象
            $que = array(
                'student_id' => $studentId,
                'class_course_id' => $classCourse->id
            );
            // 如果是二次签到更换座位，那么重新建立classDetail对象
            $classDetail = ClassDetail::get($que);
            $isUpdate = true;
        }

        // 通过座位id获取教室id，进而判断本教室是否处于上课状态
        if ($Classroom->course_id === 0 || is_null($Classroom->course_id) || $Classroom->out_time < time()) {
            return $this->error('当前教室并未开始上课', url('Student/afterSign?studentId=' . $studentId));
        } else {
            // 判断学生是否在当前课程中
            $Grades = Grade::where('course_id', '=', $Classroom->course_id)->select();
            if (sizeof($Grades) !== 0) {
                // 获取此学生和此课程对应的成绩
                $que = array(
                    'student_id' => $studentId,
                    'course_id' => $Classroom->course_id
                );
                $Grade = Grade::get($que);
                if (is_null($Grade)) {
                    return $this->error('您不在当前上课名单中,请检查上课地点是否正确', url('Student/afterSign?studentId=' . $studentId));
                }

                // 增加判断是否在签到截止时间内
                if ($Classroom->sign_deadline_time >= time() && $isUpdate === false) {
                    // 该成绩签到次数加并重新计算签到成绩和总成绩
                    $Grade->resigternum++;
                    $Grade->getAllgrade();
                }
            }
        }

        // 创建一条上课数据,首先获取该课程对应的上课缓存信息
        if (!$this->saveClassDetail($classCourse, $studentId, $seatId, $classDetail, $isUpdate)) {
            return $this->error('签到信息保存失败', url('sign?studentId=' . $studentId . '&seatId=' . $seatId));
        }

        $Seat = Seat::get($seatId);
        // 将教室座位student_id进行赋值
        $Seat->student_id = $studentId;
        $Seat->is_seated = 1;
        // dump($Seat->student_id);die();
        // 将修改后的座位对象保存
        if (!$Seat->save()) {
            return $this->error(
                '座位信息更新失败，请重新扫码',
                url('Student/aftersign?studentId=' . $studentId)
            );
        }
        $courseId = $Classroom->course_id;
        return $this->success(
            '扫码签到成功，开始上课',
            url('Student/afterSign?studentId=' . $studentId . '&seatId=' . $seatId . '&courseId=' . $courseId)
        );
    }

    /**
     * 上课座位学生信息缓存
     * @param ClassCourse 上课课程缓存信息
     * @param studentId 该座位学生id
     * @param seatId 学生所做的座位
     * @param ClassDetail 上课缓存待修改对象
     */
    public function saveClassDetail($ClassCourse, $studentId, $seatId, &$ClassDetail, $isUpdate = false)
    {
        // 如果不是更新，那么增加上课详情赋值
        if (!$isUpdate) {
            $ClassDetail->class_course_id = $ClassCourse->id;
            $ClassDetail->student_id = $studentId;
            $ClassDetail->aod_num = 0;
        }

        // 上课座位学生信息对象座位和更新时间字段进行修改
        $ClassDetail->seat_id = $seatId;
        $ClassDetail->update_time = time();

        // 将新建的对象进行保存
        return $ClassDetail->save();
    }

    // /**
    //  * 第二个人扫码同一个座位，显示已绑定
    //  * @param studentId 第二个扫码的人的id
    //  * @param Seat 扫码对应的座位对象
    //  */
    // public function checkIsSeated($studentId, $Seat) {
    //     // 实例化座位对象
    //     if ($Seat->student_id !== $studentId && time() < $Seat->Classroom->sign_deadline_time) {
    //         $Student = Student::get($Seat->student_id);
    //     }    
    // }

}