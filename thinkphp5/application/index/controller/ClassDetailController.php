<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\Classroom;
use app\common\model\Onclass;
use app\common\model\Seat;
use app\common\model\ClassCache;

/**
* 负责对上课缓存进行增删改查操作
*/
class ClassDetailController extends IndexController {
	public function index() {
		// 接收上课教室、上课课程、上课学生的id、上课签到时间、扫码的座位号
		$courseId = Request::instance()->param('courseId');
		$classroomId = Request::instance()->param('classroomId');
		$studentId = Request::instance()->param('studentId');
		$signTime = Request::instance()->param('signTime/d');
		$seatId = Request::instance()->param('seatId');

		// 新创建一个对象
		$ClassCache = new ClassCache;
		$Seat = Seat::get($seatId);

		// 调用saveClassCache方法对新建的ClassCache进行保存
		if (!$this->saveClassCache()) {
			return $this->error('上课缓存信息保存失败,请从新扫码签到')
		}
	}

	/**
	* @param $courseId 签到课程id
	* @param $classroomId 签到时所在教室id
	* @param $studentId 签到的学生id
	* @param $signTime 签到所对应的时间戳
	* @param $ClassCache 将要被保存的缓存对象
	*/
	public function saveClassCache($courseId, $classroomId, $studentId, $signTime, Classcache &$ClassCache) {
		// 将参数表中的参数赋值给新建的classcache对象
		$ClassCache->course_id = $courseId;
		$ClassCache->classroom_id = $classroomId;
		$ClassCache->student_id = $studentId;
		$ClassCache->sign_time = $signTime;

		// 将赋值后的classcache对象保存
		return $ClassCache->validate(true)->save();
	}

	/**
	 * 保存座位信息
	 */
	public function saveSeat($seatId, $studentId) {
		// 实例化Seat对象
		$Seat = Seat::get($seatId);

		// 将Seat对象对应的信息进行更新
		$Seat->is_seated = 1;
		$Seat->update_time = time();
	}
}