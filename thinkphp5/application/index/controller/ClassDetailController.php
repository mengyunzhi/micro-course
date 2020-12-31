<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\Classroom;
use app\common\model\Onclass;
use app\common\model\Seat;
use app\common\model\ClassDetail;

/**
* 负责对上课详情进行增删改查操作
*/
class ClassDetailController extends IndexController {

	/**
	 * 负责学生扫码签到后，将该学生上课信息保存
	 */
	public function index() {
		// 接收上课教室、上课课程、上课学生的id、上课签到时间、扫码的座位号
		$courseId = Request::instance()->param('courseId');
		$classroomId = Request::instance()->param('classroomId');
		$studentId = Request::instance()->param('studentId');
		$createTime = Request::instance()->param('createTime/d');
		$seatId = Request::instance()->param('seatId');

		// 新创建一个对象
		$ClassDetail = new ClassDetail;
		$Seat = Seat::get($seatId);

		// 调用saveClassCache方法对新建的ClassCache进行保存
		if (!$this->saveClassDetail($courseId, $classroomId, $studentId, $createTime, $ClassDetail)) {
			return $this->error('上课缓存信息保存失败,请从新扫码签到')
		}
	}

	/**
	* @param $courseId 签到课程id
	* @param $classroomId 签到时所在教室id
	* @param $studentId 签到的学生id
	* @param $createTime 签到所对应的时间戳
	* @param $seatId 签到对应的座位id
	* @param $ClassCache 将要被保存的缓存对象
	*/
	public function saveClassDetail($courseId, $classroomId, $studentId, $createTime, $seatId, ClassDetail &$ClassDetail) {
		// 将参数表中的参数赋值给新建的classcache对象
		$ClassDetail->course_id = $courseId;
		$ClassDetail->classroom_id = $classroomId;
		$ClassDetail->student_id = $studentId;
		$ClassDetail->create_time = $createTime;
		$ClassDetail->seat_id = $seatId;

		// 将赋值后的classcache对象保存
		return $ClassDetail->validate(true)->save();
	}

	/**
	 * 保存座位信息，将座位标志为对应学生所做的座位
	 * @param $seatId 将要被保存的座位对象的id
	 * @param $studentId 将要绑定的学生信息
	 */
	public function saveSeat($seatId, $studentId) {
		// 实例化Seat对象
		$Seat = Seat::get($seatId);

		// 将Seat对象对应的信息进行更新
		$Seat->is_seated = 1;
		$Seat->update_time = time();
	}
}