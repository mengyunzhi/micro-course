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
use app\common\model\Classcache;

/**
* 用于负责上课管理的各部分功能
*/
class OnClassController  extends IndexController
{
    public function index() {
        // 获取老师对应的ID
        $id =session('teacherId');

        // 接收reclass，该变量是用来判断是第一次设置签到时间
        $reClass = Request::instance()->param('reclass');

        // 接收教室id，接收上课签到时间
        // 由于目前没有设置扫码签到，故暂时设定classroom_id为1
        $classroomId = 1;
        $Classroom = Classroom::get($classroomId);
        // $courseId = Request::instance()->param('courseId');
        $outTime = Request::instance()->param('outTime');
        $beginTime = Request::instance()->param('beginTime');
        $courseId = 1;
        // $classroomId = Request::instance()->param('classroomId');

        //此处使用===会导致错误，增加判断传值是否字符串类型
        if (empty($reClass)) {
            // 将上课时间转换为秒，方便下面进行运算
            $beginTime = strtotime($beginTime);
            $outTime = strtotime($outTime);
        }        

        // 重新定义新的课前类，方便错误跳转，同时定义课程对象
        $PreClass = new PreClass;
        $Course = Course::get($courseId);

        // 增加判断签到时间必须设置
        $this->timeJudge($beginTime, $outTime);
        
        // 存取时间和课程id,并更新和保存
        $this->saveTime($Classroom, $beginTime, $outTime);
        $this->saveCourse($Classroom, $courseId);
        if (is_null($Classroom->validate(true)->save())) {
            return $this->error('签到时间或课程信息数据保存失败' . $PreClass->getError());
        }

        // 将时间戳转换为自己想要的时间
        $beginTime = date('Y年m月d日H时i分', $beginTime);
        $outTime = date('Y年m月d日H时i分', $outTime);

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
        $this->assign('Course', $Course);
        $this->assign('nums', $nums);
        $this->assign('Classroom', $Classroom);
        $this->assign('beginTime', $beginTime);
        $this->assign('outTime', $outTime);

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
        $courseId = 1;

        // 实例化课程和教室和beginTime
        $Classroom = Classroom::get($classroomId);
        $Course = Course::get($courseId);
        $beginTime = $Classroom->begin_time;

        // 构造查询条件数组,根据教室id和是否被坐找出被坐座位
        $que = array(
            "classroom_id"=>$classroomId,
            "isseated"=>1
        );
        
        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->select();

        // 调用clearClassroom方法，对classroom对象信息进行重置
        if(!$this->clearClassroom($Classroom)) {
            return $this->error('教室信息重置失败', $Request->header('referer'));
        }

        // 根据课程获取该课程所对应的中间表信息
        $CourseStudents = CourseStudent::where('course_id', '=', $courseId)->select();
        
        // 新建Students对象数组,新建加减分情况数组
        $Students = new Student();
        $ClassCaches = new Classcache();

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
            "isseated"=>1
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
    public function saveTime(Classroom &$Classroom, $beginTime, $outTime) {
        // 新建preclass对象，方便错误信息跳转
        $PreClass = new PreClass;

        // 进行赋值
        $Classroom->begin_time = $beginTime;
        $Classroom->out_time = $outTime;
    }

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
    * 签到时间是否合格验证
    * @param $beginTime 签到起始时间
    * @param $outTime 签到截止时间
    */
    protected function timeJudge($beginTime, $outTime) {
        // 定义课前对象
        $PreClass = new PreClass;
        $Request = Request::instance();
        // 主要为三个时间判断
        // 第一个时间判断为未设置签到时间直接点开始上课
        if($beginTime === 0 || $outTime === 0) {
            return $this->error('签到时长不得为空' . $Request->header('referer'));
        }
        // 增加判断签到时长不得大于两个小时
        if($outTime - $beginTime >= 7200) {
            return $this->error('签到时长不得高于两个小时' . $PreClass->getError());
        }
        // 增加判断开始签到时间不得大于截止签到时间
        if($beginTime >= $outTime) {
            return $this->error('签到开始时间大于签到截止时间' . $PreClass->getError());
        }

        //
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
    * @param beginTime 上课签到起始时间
    * @param Classcache 将要存储所查出上课信息数组
    */
    protected function aodHappened($courseId, $beginTime, &$ClassCaches) {
        // 构造查询条件数组
        $que = array(
            'course_id'=>$courseId,
            'begin_time'=>$beginTime
        );

        // 定义分页页数为2
        $pageSize = 2;

        // 新建一个对象数组 
        $ClassCachesTemplate = []; 

        // 根据上课课程和上课签到起始时间获取classcache信息数组，并按加减分数进行降序排列
        $ClassCachesTemplate = Classcache::order('aodnum desc')->where($que)->paginate($pageSize);
        $ClassCaches = $ClassCachesTemplate;
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
            "isseated"=>1
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
            $Seats[$i]->isseated = 0;
            $Seats[$i]->student_id = 0;
            if (!$Seats[$i]->validate(true)->save()) {
                return $this->error('座位信息重置失败', $Request->header('referer'));
            }
        }
    }
}