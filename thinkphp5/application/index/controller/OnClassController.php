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

class OnClassController  extends IndexController
{
    public function index()
    {
        // 获取老师对应的ID
        $id =session('teacherId');

        // 接收教室id，接收上课签到时间
        // 由于目前没有设置扫码签到，故暂时设定classroom_id为1
        $classroom_id = 1;
        // $classroom_id = Request::instance()->param('classroomId');
        $beginTime = Request::instance()->param('beginTime');
        $course_id = 1;
        // $course_id = Request::instance()->param('course_id');
        $outTime = Request::instance()->param('outTime');

        // 将上课时间转换为秒，方便下面进行运算
        $beginTime=strtotime($beginTime);
        $outTime=strtotime($outTime);

        //存取时间和课程id
        $this->saveTime($beginTime, $outTime);
        $this->saveCourse($course_id);

        // 重新定义新的课前类，方便错误跳转，同时定义课程对象
        $Preclass = new PreClass;
        $Course = Course::get($course_id);

        // 增加判断签到时间必须设置
        if($beginTime == ''||$outTime == '') {
            return $this->error('签到时长不得为空'.$Preclass->getError());
        }
        // 增加判断签到时长不得大于两个小时
        if($outTime - $beginTime >= 7200) {
            return $this->error('签到时长不得高于两个小时'.$Preclass->getError());
        }

        // 增加判断开始签到时间不得大于截止签到时间
        if($beginTime>=$outTime) {
            return $this->error('签到开始时间大于签到截止时间'.$Preclass->getError());
        }

        //将时间戳转换为自己想要的时间
        $beginTime = date('Y年m月d日H时i分',$beginTime);
        $outTime = date('Y年m月d日H时i分',$outTime);

        //获取已签到学生（由于还未完整完成数据传输，此时首先调用所有学生信息进行调试）
        $Students = Student::all();

        //获取学生们对应的学号，方便进行随机点名
        for($i=0;$i<sizeof($Students);$i++) {
            $nums[$i] = $Students[$i]->num; 
        }

        //传入已签到学生(还未完成扫码签到，暂时搁置)

        // 实例化老师    
        $Teacher = Teacher::get($id);
        // 实例化课程
        $Courses = Course::where('teacher_id','=',$id)->select();

        // 将上课签到时间和截止时间以及学号数组和课程信息传入V层
        $this->assign('Course',$Course);
        $this->assign('nums',$nums);
        $this->assign('beginTime',$beginTime);
        $this->assign('outTime',$outTime);
        return $this->fetch();
    }

    /**
     * todo: 获取当前正在上课的学生
     */
    public function getStudents() {
         $Students = Student::all();
         return json($Students);
    }

    /**
    * 下课所对应的action
    */
    public function afterclass()
    {
        // 接收教室对应的id
        // 接收课程对应的id

        // 将该教室所有的座位信息进行编辑，使其isseated状态变为0(未被坐)

        // 获取该课程对应的学生，统计应到学生人数

        // 返回提示信息：课程结束：应到多少人实到多少人，自动跳转到course/index界面
    }

    /** 
    * 上课进行加减分操作对应的action，与gradeaod部分重合
    */
    public function gradeaod()
    {
        // 功能与gradeaod重复，目前不打算新写，但对于跳转选择还是无法实现。
    }

    /**
    * 查看签到人数、具有返回按钮返回到上课签到界面
    */
    public function looksign()
    {
        // 接收传来的教室编号，获取该课程所对应的ID
        // $classroomId = Request::instance()->param('classroom_id');
        // $courseId = Request::instance()->param('course_id');
        $classroomId = 1;
        $courseId = 1;

        // 定义分页变量
        $pageSize = 2;

        // 实例化班级和课程
        $Classroom = Classroom::get($classroomId);
        $Course = Course::get($courseId);

        // 构造查询条件数组,根据教室id和是否被坐
        $que = array(
            "classroom_id"=>$classroomId,
            "isseated"=>1
        );
   
        // 根据该教室座位找出已被坐的座位
        $Seats = Seat::where($que)->paginate($pageSize);

        // 每页规定两个信息
        $pageSize = 2;

        // 根据课程获取该课程所对应的中间表信息
        $CourseStudents = CourseStudent::where('course_id','=',$courseId)->paginate($pageSize);
        
        // 获取学生人数，并通过学生总人数和已签学生人数获取未签学生信息
        $Students = array();
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
            if($flag == 1) {
                $Students[$count++] = $CourseStudents[$i]->student; 
            }
        } 

        // 将学生、教室、课程信息传入V层进行渲染
        $this->assign('courseStudents',$CourseStudents);
        $this->assign('Students',$Students);
        $this->assign('Classroom',$Classroom);
        $this->assign('course',$Course);
        $this->assign('Classroom',$Classroom);

        // 返回渲染后的学生信息
        return $this->fetch();

    }

    /**
    * 时间函数，用于保存该教室签到起始时间和签到结束时间
    * @param $beginTime为签到起始时间，$outTime为签到结束时间
    */
    public function saveTime($beginTime, $outTime) {
        $this->beginTime = $beginTime;
        $this->outTime = $outTime;

        // 判断是否保存成功
        if ($this->beginTime == '' || $this->outTime == '') {
            return $this->error('签到信息保存失败，请重新设置签到信息' . $Preclass->getError());
        }
    }

    /**
    * 保存签到课程信息。
    * @param $courseId为签到课程信息
    */
    public function saveCourse($courseId) {
        $this->courseId = $courseId;

        //判断课程id是否保存成功
        if ($this->courseId == 0) {
            return $this->error('课程信息保存失败，请重新选择上课课程' . $Preclass->getError());
        }
    }
}