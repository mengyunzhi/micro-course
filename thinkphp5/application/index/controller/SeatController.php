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

/**
 * 座位管理，负责座位的信息更新和信息重置等
 */
class SeatController extends controller
{
	public function Index(){
		$classroom_id = Request::instance()->param('id/d');
		$Seats = new Seat;
		if (!is_null($classroom_id)) {
			$Seats = Seat::where('classroom_id','=',$classroom_id)->select();
		}
		$this->assign('Seats',$Seats);
		return $this->fetch();
	}
	/**
	* 增加座位
	* @param $seatMapId 对应模板的id
	* @param $url 要跳到的链接
	*/
	public function add() {
	
	}
	/**
	 * 挨个copy座位
	 */
	public function saveSeat() {
		
	}

	public function edit(){
		return $this->fetch();
	}

	/**
	* 设置座位,当前状态是座位则编程过道，否则为座位.0为座位，1为过道，默认为0
	*/
	public function is_seat(){
		$id = Request::instance()->param('id\d');
		$Seat = new Seat;
		$Seat = Seat::get($id);

		if($Seat->state == "1") {

			$Seat->state = "0";
		}
		
		else {

			$Seat->state = "1";
		}
		
		$this->save();
	}
	//思路同上 1为有人，0为无人,默认为0
	public function is_seated(){
		$id = Request::instance()->param('id\d');
		$Seat = new Seat;
		$Seat = Seat::get($id);
		if($Seat->isseated == "1")
		$Seat->isseated = "0";
		else 
		$Seat->isseat = "1";
		$this->save();
	}

	/**
     * 上课签到、将上课座位属性student_id变为其id
     */
    public function sign() {
        // 首先根据微信端的Cookie值判断是否该该学生信息，并获取该学生的id信息
        $studentId = Request::instance()->param('studentId');

        // 获取学生id和教室座位id,并实例化教室座位对象
        $seatId = Request::instance()->param('seatId');
        $Seat = Seat::get($seatId);

        // 增加判断是否为已经扫码，更改座位情况
        $SeatFirst = Seat::get($studentId);
        if (!is_null($SeatFirst)) {
        	$SeatFirst->studentId = null;
        	$SeatFirst->is_seated = 0;
        	// 获取对应的上课详情对象
        	$classCourse = ClassCourse::get(['classroom_id' => $Seat->classroom_id, 'begin_time' => $Classroom->begin_time]);
        	$que = array(
        		'studentId' => $studentId,
        		'class_course_id' => $classCourse->id
        	);
        	$classDetail = ClassDetail::get($que);
        	// 修改找到的classDetail对象属性
       		$classDetail->studentId = 0;
       		$classDetail->update_time = time();
       		if (!$classDetail->save()) {
       			return $this->error('上课缓存更新失败,将重新签到', url('Login/afterSign?studentId=' . $studentId));
       		}
        }

        // 通过座位id获取教室id，进而判断本教室是否处于上课状态
        $Classroom = Classroom::get($Seat->classroom_id);
        if ($Classroom->course_id === 0) {
        	return $this->error('当前教室并未开始上课', url('Login/afterSign?studentId=' . $studentId));
        } else {
        	// 增加判断是否在签到截止时间内
        	if ($Classroom->sign_deadline_time >= time()) {
        		// 获取此学生和此课程对应的成绩
        		$que = array(
        			'student_id' => $studentId,
        			'course_id' => $Classroom->course_id
        		);
        		$Grade = Grade::get($que);
        		if (is_null($Grade)) {
        			return $this->error('您不在当前上课名单中,请检查上课课程是否正确', url('Login/afterSign?studentId=' . $studentId));
        		}
        		// 该成绩签到次数加一并保存
        		$Grade->resigternum ++;
        		if (!$Grade->save()) {
        			return $this->error('签到次数加一失败，即将重新签到', url('sign?studentId=' . $studentId . '&seatId=' . $seatId));
        		}
        	}
        }

        // 创建一条上课数据,首先获取该课程对应的上课缓存信息
        $classCourse = ClassCourse::get(['classroom_id' => $Seat->classroom_id, 'begin_time' => $Classroom->begin_time]);
        if (!$this->saveClassDetail($classCourse, $studentId, $seatId)) {
        	return $this->error('签到信息保存失败', url('sign?studentId=' . $studentId . '&seatId=' . $seatId));
        }
 
        // 将教室座位student_id进行赋值
        $Seat->student_id = $studentId;
        $Seat->is_seated = 1;

        // 将修改后的座位对象保存
        if (!$Seat->save()) {
            return $this->error('座位信息更新失败，请重新扫码', url('sign?studentId=' . $studentId));
        }
        return $this->success('扫码签到成功，请开始上课', url('Login/afterSign?studentId=' . $studentId . '&seatId=' . $seatId));
    }

    /**
     * 上课座位学生信息缓存
     * @param classCourse 上课课程缓存信息
     * @param studentId 该座位学生id
     * @param seatId 学生所做的座位
     */
    public function saveClassDetail($classCourse, $studentId, $seatId) {
    	// 新建上课座位学生信息对象并进行赋值
    	$classDetail = new ClassDetail();
    	$classDetail->class_course_id = $classCourse->id;
    	$classDetail->student_id = $studentId;
    	$classDetail->aod_num = 0;
    	$classDetail->seat_id = $seatId;
    	$classDetail->create_time = time();
    	$classDetail->update_time = time();

    	// 将新建的对象进行保存
    	return $classDetail->save();
    }
}