<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Classroom;
use app\common\model\Seat;
use app\common\model\PreClass;
use app\common\model\SeatMap;
use app\common\model\ClassDetail;
use app\common\model\ClassCourse;
use Env;
use PHPExcel_IOFactory;
use PHPExcel;


/**
* 用于负责上课管理的各部分功能
*/
class InClassController  extends IndexController {
    /**
     * 首页负责展示上课座位图和上课基本信息
     */
    public function index() {
        // 获取老师对应的ID,并实例化教师对象
        $id =session('teacherId');
        $Teacher = Teacher::get($id);

        // 接收reclass，该变量是用来判断是第一次设置签到时间
        $reClass = Request::instance()->param('reclass/d');

        // 接收教室id，微信端登陆后绑定教室信息到教师中
        $classroomId = $Teacher->classroom_id;
        if (is_null($Classroom = Classroom::get($classroomId))) {
            return $this->error('教室信息获取失败，请重新上课', url('Course/index'));
        }

        // 根据教室获得对应的座位,同时获取教室对应的座位图模板
        $seats = Seat::where('classroom_id', '=', $classroomId)->select();
        $SeatTemplate = SeatMap::get($Classroom->seat_map_id);

        // 将教室按照先x后y排序，并将排序结果保存
        $newSeats = $this->seatDisplay($seats, $SeatTemplate);

        // 首先判断是不是已经设置上课时间等
        if ($reClass !== 1) {
            // 如果是已经上课，就不需要重新接收courseId
            $courseId = Request::instance()->param('courseId');
            // 接受上课时间和课程ID
            $beginTime = Request::instance()->param('beginTime');

            // 增加判断签到时间必须设置
            $this->timeJudge($beginTime, $Classroom);

            // 重新定义新的课前类，方便错误跳转，同时定义课程对象
            $PreClass = new PreClass;
            
            // 存取时间和课程id,并更新和保存
            $this->saveCourse($Classroom, $courseId);
            if (is_null($Classroom->validate(true)->save())) {
                return $this->error('签到时间或课程信息数据保存失败', Request::instance()->header('referer'));
            }

            // 将上课信息记录到ClassCourse类中
            $this->saveClassCourse($Classroom, $courseId);
        }

        // 通过date函数将时间转换为小时/分钟形式，便于判断
        $outTime = date('G/i', $Classroom->out_time);

        // 获取已签到学生（由于还未完整完成数据传输，此时首先调用所有学生信息进行调试）
        $Students = Student::all();

        // 获取学生们对应的学号，方便进行随机点名
        for($i = 0; $i < sizeof($Students); $i++) {
            $nums[$i] = $Students[$i]->num; 
        }

        // 将上课签到时间和截止时间以及学号数组和课程信息和座位图模板传入V层
        $this->assign('Course', $Classroom->Course);
        $this->assign('nums', $nums);
        $this->assign('seats', $newSeats);
        $this->assign('Classroom', $Classroom);
        $this->assign('outTime', $outTime);
        $this->assign('SeatTemplate', $SeatTemplate);
        $this->assign('classroomId', $classroomId);

        //取回V层渲染的
        return $this->fetch();
    }

    /**
     * todo: 获取当前正在上课的学生，用于VUE实现的随机点名
     */
    public function getStudents() {
        $classroomId = Request::instance()->param('classroomId');
        return json($this->classStudents($classroomId));
    }

    /**
     * 獲取學生信息
     */
    public function classStudents($classroomId) {
        // 接收教室id，并根据教室对象获取上课课程缓存对象
        // dump($classroomId);

        $Classroom = Classroom::get($classroomId);
        // dump($Classroom);
        $que = array(
            'begin_time' => $Classroom->begin_time,
            'classroom_id' => $Classroom->id
        );

        // 通过构造的查询数组获取此时对应的教室上课缓存
        $ClassCourse = ClassCourse::get($que);

        // 通过上课课程缓存获取上课详情缓存
        $classDetails = ClassDetail::where('class_course_id', '=', $ClassCourse->id)->select();
        $number = sizeof($classDetails);
        $Students = [];

        // 通过获取到的上课缓存获取到当前上课的学生对象数组
        for ($i = 0; $i < $number; $i++) {
            $Students[$i] = Student::get($classDetails[$i]->student_id);
        }
        // dump($Students);
        return $Students;
    }

    /**
    * 下课所对应的action
    */
    public function afterClass() {
        // 实例化请求
        $Request = Request::instance();

        // 接收教室对应的id,接收课程对应的id
        $classroomId = Request::instance()->param('classroomId/d');
        $courseId = Request::instance()->param('courseId/d');
        
        // 实例化课程和教室和beginTime
        $Classroom = Classroom::get($classroomId);
        $Course = Course::get($courseId);
        $beginTime = $Classroom->begin_time;

        // 构造查询条件数组,根据教室id和是否被坐找出被坐座位
        $que = array(
            "classroom_id"=>$classroomId,
            "is_seated"=>1
        );
        
        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->select();

        // 通过教室对象信息获取当前上课课程缓存信息
        $ClassCourse = ClassCourse::get(['classroom_id' => $Classroom->id, 'begin_time' => $Classroom->begin_time]);

        if(is_null($ClassCourse)) {
            return $this->error('上课课程缓存未找到', url('PreClass/index?classroomId=' . $Classroom->id));
        }

        // 调用clearClassroom方法，对classroom对象信息进行重置
        if(!$this->clearClassroom($Classroom)) {
            return $this->error('教室信息重置失败', $Request->header('referer'));
        }

        // 根据课程获取该课程所对应的中间表信息
        $CourseStudents = CourseStudent::where('course_id', '=', $courseId)->select();
        
        // 新建学生和上课缓存数组
        $Students = [];
        $classDetails = [];

        // 调用unsignStu函数和aodHappened函数获取未签到学生和上课缓存信息
        $this->unsignStu($CourseStudents, $Seats, $Students);
        $this->aodHappened($courseId, $ClassCourse->id, $classDetails);

        // 返回提示信息：课程结束：显示应到多少人实到多少人，加减分情况
        $this->assign('students', $Students);
        $this->assign('courseStudents', $CourseStudents);
        $this->assign('ClassCourse', $ClassCourse);
        $this->assign('classDetails', $classDetails);
        $this->assign('Course', $Course);
        $this->assign('beginTime', $beginTime);

        return $this->fetch();
    }

    /**
    * 查看签到人数、具有返回按钮返回到上课签到界面
    */
    public function lookSign() {
        // 接收传来的教室编号，获取该课程所对应的ID
        $classroomId = Request::instance()->param('classroomId');
        $courseId = Request::instance()->param('courseId');
        // $classroomId = 35;

        // 定义分页变量
        $pageSize = 2;

        // 实例化班级和课程
        $Classroom = Classroom::get($classroomId);
        $Course = Course::get($courseId);
        $beginTime = $Classroom->begin_time;

        // 构造查询条件数组,根据教室id和是否被坐
        $que = array(
            "classroom_id"=>$classroomId,
            "is_seated"=>1
        );

        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->select();

        // 根据课程获取该课程所对应的中间表信息
        $CourseStudents = CourseStudent::where('course_id', '=', $courseId)->select();
        
        // 新建学生和上课缓存数组
        $Students = [];
        $ClassDetails = [];

        // 获取上课缓存数组，进而获得已签名单和签到时间
        $ClassCourse = ClassCourse::get(['classroom_id' => $classroomId, 'begin_time' => $Classroom->begin_time]);
        $ClassDetails = ClassDetail::where('class_course_id', '=', $ClassCourse->id)->paginate($pageSize); 
        
        // 将Students，CourseStudents,Seats传入求未签到学生的函数
        $this->unsignStu($CourseStudents, $Seats, $Students);

        // 将学生、教室、课程信息传入V层进行渲染
        $this->assign('courseStudents', $CourseStudents);
        $this->assign('Students', $Students);
        $this->assign('ClassDetails', $ClassDetails);
        $this->assign('Classroom', $Classroom);
        $this->assign('course', $Course);
        $this->assign('Classroom', $Classroom);

        // 返回渲染后的学生信息
        return $this->fetch();
    }

    /**
    * 时间函数，用于保存该教室签到起始时间和签到结束时间
    * @param $beginTime 签到起始时间
    * @param $outTime 签到结束时间
    * @param $Classroom 时间保存到的教室对象
    */
    // public function saveTime(Classroom &$Classroom, $beginTime, $outTime) {
    // 新建preclass对象，方便错误信息跳转
        // $PreClass = new PreClass;

        // 进行赋值
        // $Classroom->begin_time = $beginTime;
        // $Classroom->out_time = $outTime;
    // }

    /**
    * 保存签到课程信息。
    * @param $courseId 签到课程信息
    * @param $Classroom 课程存储对应的教室
    */
    public function saveCourse(Classroom &$Classroom,$courseId) {
        // 新建preclass对象，方便错误信息跳转
        $Preclass = new PreClass;

        // $Classroom->course_id = $courseId;
        $Classroom->course_id = $courseId;

        // 修改课程对应的信息(上课签到次数)
        // 首先实例化课程对象
        $Course = Course::get($Classroom->course_id);

        // 更改课程的签到次数
        $Course->resigternum ++;

        // 将更改后的课程信息保存
        if (!$Course->save()) {
            return $this->error('课程签到次数增加失败,请重新开始上课', url('PreClass/index?classroomId=' . $Classroom->id));
        }
    }

    /**
    * 检验上课时间是否合格，同时设置上课时间和签到截止时间
    * @param $beginTime 签到起始时间
    * @param $outTime 签到截止时间
    * @param Classroom 待修改的教室对象
    */
    protected function timeJudge($beginTime, Classroom &$Classroom) {
        // 定义课前对象
        $PreClass = new PreClass;
        $Request = Request::instance();
        // 主要为三个时间判断
        // 第一个时间判断为未设置签到时间直接点开始上课
        if($beginTime === 0) {
            return $this->error('上课时间获取失败' . $Request->header('referer'));
        }

        // 增加判断上课时间为课前或课中两种情况,并分不同情况得出签到截止时间
        // 第一种：课中
        // 将时间转换为小时/分钟的形式
        $Classroom->begin_time = $beginTime;
        $string = date('d F Y', $beginTime);
        $firstTime = $string . ' 8hours 30minutes';
        $secondTime = $string . ' 10hours +5minutes';
        $thirdTime = $string . ' 10hours 25minutes';
        $fourthTime = $string . ' 12hours';
        $fifthTime = $string . ' 14hours';
        $sixthTime = $string . ' 15hours 35minutes';
        $seventhTime = $string . ' 15hours 55minutes';
        $eighthTime = $string . ' 17hours 30minutes';
        $ninthTime = $string . ' 18hours 40minutes';
        $tenthTime = $string . ' 20hours 15minutes';

        // 将获取到的时间转换为时间戳的形式
        $firstTime = strtotime($firstTime);
        $secondTime = strtotime($secondTime);
        $thirdTime = strtotime($thirdTime);
        $fourthTime = strtotime($fourthTime);
        $fifthTime = strtotime($fifthTime);
        $sixthTime = strtotime($sixthTime);
        $seventhTime = strtotime($seventhTime);
        $eighthTime = strtotime($eighthTime);
        $ninthTime = strtotime($ninthTime);
        $tenthTime = strtotime($tenthTime);
        if($beginTime >= $firstTime && $beginTime <= $secondTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $secondTime;
        }
        if($beginTime >= $thirdTime && $beginTime <= $fourthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $fourthTime;
        }
        if($beginTime >= $fifthTime && $beginTime <= $sixthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $sixthTime;
        }
        if($beginTime >= $seventhTime && $beginTime <= $eighthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $eighthTime;
        }
        if($beginTime >= $ninthTime && $beginTime <= $tenthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $tenthTime;
        }
        if($beginTime <= $firstTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $firstTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $secondTime;
        }
        if($beginTime <= $thirdTime && $beginTime >= $secondTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $thirdTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $fourthTime;
        }
        if($beginTime <= $fifthTime && $beginTime >= $fourthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $fifthTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $sixthTime;
        }
        if($beginTime <= $seventhTime && $beginTime >= $sixthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $seventhTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $eighthTime;
        }
        if($beginTime <= $ninthTime && $beginTime >= $eighthTime) {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $ninthTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time * 60;;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = $tenthTime;
        }
        return $Classroom->validate(true)->save();
        //
    }

    /**
     * 根据教室对象修改签到时长方法
     */
    public function changeSignTime() {
        // 接收改变后的sigiTime和教室id：classroomId
        $signTime = Request::instance()->param('signTime');
        $classroomId = Request::instance()->param('classroomId');

        // 实例化教室对象
        $Classroom = Classroom::get($classroomId);

        // 将signTime(分钟)赋值给我教室的sign_time属性
        $Classroom->sign_time = $signTime;

        // 根据上课时间和修改后的签到时长，从新设置签到截止时长
        $Classroom->sign_deadline_time = $Classroom->sign_time * 60 + $Classroom->sign_begin_time;

        // 将修改后的教室对象保存,并判断是否保存成功
        if(!$Classroom->validate(true)->save()) {
            return $Classroom->getError('签到时长修改失败', url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1));
        }
        return $this->success('修改签到时长成功', url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1));
    }

    /**
     * 保存上课信息
     * @param Classroom 教室对象
     * @param courseId 课程id
     */
    public function saveClassCourse($Classroom, $courseId) {
        $ClassCourse = new ClassCourse;
        $ClassCourse->course_id = $courseId;
        $ClassCourse->begin_time = $Classroom->begin_time;
        $ClassCourse->out_time = $Classroom->out_time;
        $ClassCourse->classroom_id = $Classroom->id;
        $Course = course::get($courseId);
        $ClassCourse->teacher_id = $Course->teacher_id;

        // 将上课课程信息保存
       if (!$ClassCourse->save()) {
        return $this->error('上课课程信息缓存失败，请重新开始上课', url('PreClass/index'));
       }
    }

    /**
     * 改变教室对象的下课时间
     */
    public function changeOutTime() {
        // 实例化请求对象,接收从index传来的下课时间序列和教室Id classroomId
        $Request = Request::instance();
        $outTime = Request::instance()->param('outTime/d');
        $classroomId = Request::instance()->param('classroomId');

        // 实例化教室对象
        $Classroom = Classroom::get($classroomId);

        $string = date('d F Y', $Classroom->out_time);
        $secondTime = $string . ' 10hours +5minutes';
        $fourthTime = $string . ' 12hours';
        $sixthTime = $string . ' 15hours 35minutes';
        $eighthTime = $string . ' 17hours 30minutes';
        $tenthTime = $string . ' 20hours 15minutes';

        // 将时间转换为时间戳形式
        $secondTime = strtotime($secondTime);
        $fourthTime = strtotime($fourthTime);
        $sixthTime = strtotime($sixthTime);
        $eighthTime = strtotime($eighthTime);
        $tenthTime = strtotime($tenthTime);

        // 将获取到的下课截止时间赋值给Classroom对象
        if($outTime === 1) {
            $Classroom->out_time = $secondTime;
        }
        if($outTime === 2) {
            $Classroom->out_time = $fourthTime;
        }
        if($outTime === 3) {
            $Classroom->out_time = $sixthTime;
        }
        if($outTime === 4) {
            $Classroom->out_time = $eighthTime;
        }
        if($outTime === 5) {
            $Classroom->out_time = $tenthTime;
        }

        // 将修改后的教室对象保存,并判断是否保存成功
        if(!$Classroom->validate(true)->save()) {
            return $this->error('下课时间修改失败', url('index?classroomId=' . $Classroom->id . '&reclass=' . 1));
        }
        return $this->success('修改下课时间成功', url('index?classroomId=' . $Classroom->id . '&reclass=1'));
    }

    /**
    * 获取未签到学生
    * @param $CourseStudents 根据课程id得出的中间表
    * @param $Seats 传入的已被坐的座位
    * @param $Students 传入的用于存储未签到学生的对象数组
    */
    public function unsignStu($CourseStudents, $Seats, &$Students) {
        // 获取学生人数，并通过学生总人数和已签学生人数获取未签学生信息
        //number为学生总人数，havenumber为已签到学生人数，count负责计数方便将学生信息存入学生对象数组
        $count = 0;
        $number = sizeof($CourseStudents);
        $haveNumber = sizeof($Seats);
        for ($i = 0; $i < $number; $i++) {
            $flag = 1;
            for ($j = 0; $j < $haveNumber; $j++) {
                if ($CourseStudents[$i]->student == $Seats[$j]->student) {
                    $flag = 0;
                }
            } 
            if($flag === 1) {
                $Students[$count++] = $CourseStudents[$i]->student; 
                // dump($Students);
            }
        }
    }

    /**
    * 获取加减分情况,理论上有课程id和上课起始时间就行
    * @param courseId 上课的课程
    * @param classCourseId 对应的课程上课信息id
    * @param ClassDetails 接收本节课学生上课信息的数组
    */
    protected function aodHappened($courseId, $classCourseId, array &$ClassDetails) {
        // 定义分页页数为2
        $pageSize = 2;

        // 新建一个对象数组 
        $ClassDetailsTemplate = []; 

        // 根据上课课程和上课签到起始时间获取classDetail信息数组，并按加减分数进行降序排列
        $ClassDetailsTemplate = ClassDetail::order('aod_num desc')->where('class_course_id', '=', $classCourseId)->paginate($pageSize);
        $ClassDetails = $ClassDetailsTemplate;
        // rsort($Classcaches);
    }

    /**
    * 清除教室中保留的上节课信息
    * @param $Classroom 被清除教室对象
    */
    protected function clearClassroom(Classroom &$Classroom) {
        // 实例化请求
        $Request = Request::instance();

        // 构造查询条件数组,根据教室id和是否被坐找出被坐座位
        $que = array(
            "classroom_id"=>$Classroom->id,
            "is_seated"=>1
        );

        // 实例化教师对象，清除教师对象中的classroom_id字段内容
        $Teacher = Teacher::get($Classroom->Course->Teacher->id);
        $Teacher->classroom_id = 0;
        $Teacher->save();
        
        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->select();

        // 调用clearSeats方法对已做座位进行信息清空
        $this->clearSeats($Seats);

        // 将该教室对象的各个数据进行清空
        $Classroom->begin_time = 0;
        $Classroom->out_time = 0;
        $Classroom->course_id = 0;
        $Classroom->sign_time = 20;
        $Classroom->sign_deadline_time = 0;
        $Classroom->update_time = time();
        $Classroom->out_time = 0;
        $Classroom->begin_time = 0;
        $Classroom->sign_begin_time = 0;

        // 更新并保存数据
        $Classroom->validate(true)->save();
        return 1;
    }

    /**
    * 清除教室对应的座位的座位信息
    * @param $Seats 将被清除的座位对象数组
    */
    protected function clearSeats(array &$Seats) {
        // 将该教室的各个座位的信息清空
        // 首先得出该教室中的座位个数
        $number = sizeof($Seats);

        // 对该教室的每个座位信息进行逐个清空
        for ($i = 0; $i < $number; $i++) {
            $Seats[$i]->is_seated = 0;
            $Seats[$i]->student_id = 0;
            if (!$Seats[$i]->validate(true)->save()) {
                return $this->error('座位信息重置失败', $Request->header('referer'));
            }
        }
    }

    /**
     * 转化座位为二维数组
     * @param $seats 要展示的座位数组
     * @param $SeatMap 对应的模板
     */
    public function seatDisplay($seats, $SeatMap) {
      // 定义一个数组，将座位转换为二维数组按先x后y的方式排好序
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
     * @param $classroomId 将要输出的教室对象的id
     */
    public function seatingPlan($classroomId) {
      // 根据教室id查找出该教室的所有座位，同时对教室进行实例化
      $seats = Seat::where('classroom_id', '=', $classroomId)->select();
      $Classroom = Classroom::get($classroomId);

      // 通过教室id获取对应的教室模板，并判断是否存在
      $SeatMap = SeatMap::get($Classroom->seat_map_id);
      if(empty($SeatMap)) {
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
     * 用于测试,以便获得各个时间段对应的时间戳
     */
    public function test() {
       $string = date('Y/m/d/G/i',1609287900); return  $string ;
    }

    /**
     * 文件导出上課表現成绩部分
     */
    public function fileExportGrade() {
        // 获取时间和课程名，以用作文件名
        $time = input('param.time');
        $courseName = input('param.courseid');
        require_once dirname(__FILE__) . '/../PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties

        $objPHPExcel->getProperties()->setCreator("Liting Chen")//创立者
                                     ->setLastModifiedBy("yunzhi")//最后修改者
                                     ->setTitle($time . $courseName)//文件名，以下的不用动
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");

        // 添加数据
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '序号')
                    ->setCellValue('B1', '姓名')
                    ->setCellValue('C1', '学号')
                    ->setCellValue('D1', '性别')
                    ->setCellValue('E1', '加减分情况')
                    ->setCellValue('F1', '签到时间');

                    // 利用foreach循环将数据库中的数据读出，下面仅仅是将学生表的数据读出
                    $classCourseId = Request::instance()->param('classCourseId');
                    $classDetails = ClassDetail::where('class_course_id', '=', $classCourseId)->select();
                    $count = 2;
                    foreach ($classDetails as $ClassDetail) {
                        $Student = Student::get($ClassDetail->student_id);
                        // Miscellaneous glyphs, UTF-8
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A' . $count, $count-1)
                                    ->setCellValue('B' . $count, $Student->name)
                                    ->setCellValue('C' . $count, $Student->num)
                                    ->setCellValue('E' . $count, $ClassDetail->aod_num)
                                    ->setCellValue('F' . $count, date('Y/m/d G:i', $ClassDetail->update_time));
                        if($Student->sex === 0) {
                                $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $count, '男');
                        } else {
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $count, '女');
                        }
                        $count++;
                    }
       

        // 导出的Excel表的表名，不是文件名
        $objPHPExcel->getActiveSheet()->setTitle('上课情况汇总');

        //必须要有，否则导出的Excel用不了，设定活跃的表是哪个，设定的活跃表是表0
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit; 
    }

    /**
     * 将未签到的学生信息以Excel的形式输出
     */
    public function fileExportSign() {
        // 接收课程id并进行实例化，接收上课课程缓存id
        $courseId = Request::instance()->param('courseId');
        $classCourseId = Request::instance()->param('classCourseId');

        // 通过上课课程id获取上课详情对象数组
        $ClassDetails = ClassDetail::where('class_course_id', '=', $classCourseId)->select();
        $CourseStudents = CourseStudent::where('course_id', '=', $courseId)->select();
        $number = sizeof($CourseStudents) - sizeof($ClassDetails);
        $count = 0;
        $Students = [];
        // 獲取未簽到的學生
        for ($i = 0; $i < $number; $i++) {
            $flag = 1;
            for($j = 0; $j < sizeof($ClassDetails); $j++) {
                if ($ClassDetails[$j]->student_id === $CourseStudents[$i]->student_id) {
                    $flag = 0;
                }
            }
            if ($flag === 1) {
                $Students[$count++] = $CourseStudents[$i]->student;
            }
        }

        require_once dirname(__FILE__) . '/../PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Liting Chen")//创立者
                                     ->setLastModifiedBy("yunzhi")//最后修改者
                                     ->setTitle("Office 2007 XLSX Test Document")//文件名，以下的不用动
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");

        // 添加数据
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '序号')
                    ->setCellValue('B1', '姓名')
                    ->setCellValue('C1', '学号')
                    ->setCellValue('D1', '性别');
                    
                    // 利用foreach循环将数据库中的数据读出，下面仅仅是将学生表的数据读出
                    $count = 2;
                    foreach ($Students as $Student) {
                        // Miscellaneous glyphs, UTF-8
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A' . $count, $count-1)
                                    ->setCellValue('B' . $count, $Student->name)
                                    ->setCellValue('C' . $count, $Student->num)
                                    ->setCellValue('D' . $count, $sex = $Student->sex === 0 ? '男' : '女');
                        $count++;
                    }

        // 导出的Excel表的表名，不是文件名
        $objPHPExcel->getActiveSheet()->setTitle('上课情况汇总');

        // 必须要有，否则导出的Excel用不了，设定活跃的表是哪个，设定的活跃表是表0
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit; 
    }
}