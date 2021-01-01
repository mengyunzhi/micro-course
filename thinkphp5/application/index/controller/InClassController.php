<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\SeatMapTeacher;
use app\common\model\Classroom;
use app\common\model\Seat;
use app\common\model\PreClass;
use app\common\model\ClassDetail;

/**
* 用于负责上课管理的各部分功能
*/
class InClassController  extends IndexController
{
    public function index() {
        // 获取老师对应的ID
        $id =session('teacherId');

        // 接收reclass，该变量是用来判断是第一次设置签到时间
        $reClass = Request::instance()->param('reclass');

        // 接收教室id，接收上课签到时间
        // 由于目前没有设置扫码签到，故暂时设定classroom_id为1
        $classroomId = Request::instance()->param('classroomId');
        $classroomId = 1;
        $Classroom = Classroom::get($classroomId);

        // 首先判断是不是已经设置上课时间等
        if (empty($reClass)) {
            // 如果是已经上课，就不需要重新接收courseId
            // $courseId = Request::instance()->param('courseId');
            // 接受上课时间和课程ID
            $beginTime = Request::instance()->param('beginTime');
            $courseId = 1;

            // 增加判断签到时间必须设置
            $this->timeJudge($beginTime, $Classroom);

            // 重新定义新的课前类，方便错误跳转，同时定义课程对象
            $PreClass = new PreClass;
            
            // 存取时间和课程id,并更新和保存
            $this->saveCourse($Classroom, $courseId);
            if (is_null($Classroom->validate(true)->save())) {
                return $this->error('签到时间或课程信息数据保存失败' . $PreClass->getError());
            }
        }

        // 获取已签到学生（由于还未完整完成数据传输，此时首先调用所有学生信息进行调试）
        $Students = Student::all();

        // 获取学生们对应的学号，方便进行随机点名
        for($i = 0; $i < sizeof($Students); $i++) {
            $nums[$i] = $Students[$i]->num; 
        }

        // 传入已签到学生(还未完成扫码签到，暂时搁置)

        // 实例化老师,根据教师ID获取课程   
        $Teacher = Teacher::get($id);
        $Courses = Course::where('teacher_id', '=', $id)->select();

        // 将上课签到时间和截止时间以及学号数组和课程信息传入V层
        $this->assign('Course', $Classroom->Course);
        $this->assign('nums', $nums);
        $this->assign('Classroom', $Classroom);

        //取回V层渲染的
        return $this->fetch();
    }

    /**
     * todo: 获取当前正在上课的学生，用于VUE实现的随机点名
     */
    public function getStudents() {
         $Students = Student::all();
         return json($Students);
    }

    /**
    * 下课所对应的action
    */
    public function afterClass() {
        // 实例化请求
        $Request = Request::instance();

        // 接收教室对应的id,接收课程对应的id
        // $classroomId = Request::instance()->param('$classroomId');
        $classroomId = 1;
        $courseId = 3;

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

        // 调用clearClassroom方法，对classroom对象信息进行重置
        if(!$this->clearClassroom($Classroom)) {
            return $this->error('教室信息重置失败', $Request->header('referer'));
        }

        // 根据课程获取该课程所对应的中间表信息
        $CourseStudents = CourseStudent::where('course_id', '=', $courseId)->select();
        
        // 新建学生和上课缓存数组
        $Students = [];
        $ClassCaches = [];

        // 调用unsignStu函数和aodHappened函数获取未签到学生和上课缓存信息
        $this->unsignStu($CourseStudents, $Seats, $Students);
        $this->aodHappened($courseId, $beginTime, $ClassCaches);

        // 返回提示信息：课程结束：显示应到多少人实到多少人，加减分情况
        $this->assign('students', $Students);
        $this->assign('courseStudents', $CourseStudents);
        $this->assign('ClassCaches', $ClassCaches);
        $this->assign('Course', $Course);

        return $this->fetch();
    }

    /** 
    * 上课进行加减分操作对应的action，与gradeaod部分重合
    */
    public function gradeAod() {
        // 功能与gradeaod重复，目前不打算新写，但对于跳转选择还是无法实现。
    }

    /**
    * 查看签到人数、具有返回按钮返回到上课签到界面
    */
    public function lookSign() {
        // 接收传来的教室编号，获取该课程所对应的ID
        // $classroomId = Request::instance()->param('classroom_id');
        // $courseId = Request::instance()->param('course_id');
        $classroomId = 1;
        $courseId = 3;

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
        $ClassCaches = [];
        
        // 将Students，CourseStudents,Seats传入求未签到学生的函数
        $this->unsignStu($CourseStudents, $Seats, $Students);
        $this->aodHappened($courseId, $beginTime, $ClassCaches);

        // 将学生、教室、课程信息传入V层进行渲染
        $this->assign('courseStudents', $CourseStudents);
        $this->assign('Students', $Students);
        $this->assign('ClassCaches', $ClassCaches);
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

        // 进行赋值由于未传值，故用3表示
        // $Classroom->course_id = $courseId;
        // 老师，因为那个数据库字段没有courseId，我改了之后就保错了
        $Classroom->course_id = 3;
    }

    /**
    * 检验上课时间是否合格，同时设置上课时间和签到截止时间
    * @param $beginTime 签到起始时间
    * @param $outTime 签到截止时间
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
        $string = date('G/i', $beginTime);
        if($string >= '08/30' && $string <= '10/05') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $string = date('G/i', $signDeadlineTime);
            $signDeadlineTime = $string;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609293900;
        }
        if($string >= '10/25' && $string <= '12/00') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $string = date('G/i', $signDeadlineTime);
            $signDeadlineTime = $string;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609300800;
        }
        if($string >= '14/00' && $string <= '15/35') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $string = date('G/i', $signDeadlineTime);
            $signDeadlineTime = $string;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609313700;
        }
        if($string >= '15/55' && $string <= '17/30') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $string = date('G/i', $signDeadlineTime);
            $signDeadlineTime = $string;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609320600;
        }
        if($string >= '18/40' && $string <= '20/15') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = $beginTime;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $string = date('G/i', $signDeadlineTime);
            $signDeadlineTime = $string;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609330500;
        }
        if($string <= '08/30') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = 1609288200;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609293900;
        }
        if($string <= '10/25' && $string >= '10/05') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = 1609295100;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609300800;
        }
        if($string <= '14/00' && $string >= '12/00') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = 1609308000;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609313700;
        }
        if($string <= '15/55' &&$string >= '15/35') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = 1609314900;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609320600;
        }
        if($string >= '17/30' && $string <= '18/40') {
            $Classroom->sign_time = 20;
            $Classroom->sign_begin_time = 1609324800;
            $signDeadlineTime = $Classroom->sign_begin_time + $Classroom->sign_time;
            $Classroom->sign_deadline_time = $signDeadlineTime;
            $Classroom->out_time = 1609330500;
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

        // 将signTime赋值给我教室的sign_time属性
        $Classroom->sign_time = $signTime;

        // 根据上课时间和修改后的签到时长，从新设置签到截止时长
        $Classroom->sign_deadline_time = $Classroom->sign_time + $Classroom->sign_begin_time;

        // 将修改后的教室对象保存,并判断是否保存成功
        if(!$Classroom->validate(true)->save()) {
            return $Classroom->getError('签到时长修改失败', url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1));
        }
        return $this->success('修改签到时长成功', url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1));
    }

    /**
     * 改变教室对象的下课时间
     */
    public function changeOutTime() {
        // 实例化请求对象,接收从index传来的下课时间outTime和教室Id classroomId
        $Request = Request::instance();
        $outTime = Request::instance()->param('outTime');
        $classroomId = Request::instance()->param('classroomId');

        // 实例化教室对象
        $Classroom = Classroom::get($classroomId);

        // 增加判断：当前截止时间和修改后的是否一直
        if($Classroom->out_time === $outTime) {
            return $this->success('修改下课时间成功', url('index?classroomId=' . $Classroom->id . '&reclass=' . 1));
        }

        // 将获取到的下课截止时间赋值给Classroom对象
        $Classroom->out_time = $outTime;

        // 将修改后的教室对象保存,并判断是否保存成功
        if(!$Classroom->validate(true)->save()) {
            return $Classroom->error('下课时间修改失败', url('index?classroomId=' . $Classroom->id . '&reclass=' . 1));
        }
        return $this->success('修改下课时间成功', url('index?classroomId=' . $Classroom->id . '&reclass=' . 1));
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
    protected function aodHappened($courseId, $classCourseId,array &$ClassDetails) {
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
        
        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->select();

        // 调用clearSeats方法对已做座位进行信息清空
        $this->clearSeats($Seats);

        // 将该教室对象的各个数据进行清空
        $Classroom->begin_time = 0;
        $Classroom->out_time = 0;
        $Classroom->course_id = 0;

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
     * 用于测试,以便获得各个时间段对应的时间戳
     */
    public function test() {
       $string = date('Y/m/d/G/i',1609287900); return  $string ;
    }
}