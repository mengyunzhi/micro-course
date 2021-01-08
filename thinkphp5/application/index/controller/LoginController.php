<?php
namespace app\index\controller;
use think\Controller;
use think\Request;     //请求
use app\common\model\Teacher; //教师模型
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\ClassDetail;
use app\common\model\Grade;
use app\common\model\Seat;
use app\common\model\Classroom;
use app\common\model\ClassCourse;

/**
 * 负责教师和学生扫码登陆
 */
class LoginController extends Controller
    {
    //用户登录表单
    public function index()
    {
        //显示登录表单
        return $this->fetch();
    }
    // 处理用户提交的登录数据
    public function login()
    {
        // 接收post信息
        $username = Request::instance()->param('username');
        $password = Request::instance()->param('password');
        
        // 直接调用M层方法，进行登录。
        if (Teacher::login($username, $password)) {
            // 判断是否为管理员，如果用户名是管理员则认定为管理员，跳转到管理员端
            if($username === 'admin') {
                return $this->success('login success', url('Term/index'));
            }
            // 如果不是则认定为教师端登陆，跳转到教师端
            return $this->success('login success', url('Course/index'));
        } else {
            return $this->error('username or password incorrent', url('index'));
        }
    }

    /**
     * 负责教室端微信登陆，理论上与网页端不冲突
     */
    public function wxTeacher() {
        // 接收用户名密码信息和教室id
        $username = Request::instance()->post('username');
        $password = Request::instance()->post('password');
        $classroomId = Request::instance()->param('classroomId');
        if (is_null($classroomId)) {
            return $this->error('教室信息传递失败，请从新扫码', url('Course/index'));
        }

        // 获取教师id，并判断是否存在teacherId;接收教室id,并将其存入session中
        $teacherId = session('teacherId');

        // 判断该老师是不是第一次登陆
        if (is_null($teacherId)) {
            // 首先判断用户名密码是否输入完整，如果不完整重新输入信息
            if (is_null($username) || is_null($password)) {
                return $this->error('请输入注册信息', url('teacherFirst?classroomId=' . $classroomId));
            } else {
                // 调用M层的方法对用户名密码进行判断
                if (Teacher::login($username, $password)) {
                    // 如果不是则认定为教师端登陆，跳转到教师端
                    // 如果登陆成功后，实例化教师对象，并修改教师classroom_id属性
                    // 首先清除上一个教师和教室的绑定
                    $this->clearContact($classroomId);
                    $teacherId = session('teacherId');
                    $Teacher = Teacher::get($teacherId);
                    $Teacher->classroom_id = $classroomId;
                    if (!$Teacher->save()) {
                        return $this->error('教室-老师信息绑定失败，请重新扫码', url('Course/index'));
                    }
                    return $this->success('login success', url('Course/index'));
                } else {
                    return $this->error('username or password incorrent', url('teacherFirst?classroomId=' . $classroomId));
                }
            }
        } else {
            // 如果登陆成功后，实例化教师对象，并修改教师classroom_id属性
            // 首先清除上一个教师和教室的绑定
            $this->clearContact($classroomId);
            $Teacher = Teacher::get($teacherId);
            $Teacher->classroom_id = $classroomId;
            if (!$Teacher->save()) {
                return $this->error('教室-老师信息保存失败，请重新扫码', url('Course/index'));
            }
            return $this->success('login success', url('Course/index'));
        }
    }

    /**
     * 负责老师的第一次登陆注册
     */
    public function teacherFirst() {
        // 获取教室id
        $classroomId = Request::instance()->param('classroomId');

        // 将教室id传入v层
        $this->assign('classroomId', $classroomId);
        return $this->fetch();
    }

    /**
     * 负责学生第一次微信登陆的注册任务
     */
    public function firstWx() {
        // 获取从wxLogin传出的seatId
        $seatId = Request::instance()->param('seatId');

        // 将$seatId传入V层
        $this->assign('seatId', $seatId);
        // 直接到V层渲染
        return $this->fetch();
    }
    
    /**
     * 学生微信登陆
     */
    public function wxLogin() {
        // 接收post信息,并获取学生id
        $studentName = Request::instance()->post('studentName');
        $studentNum = Request::instance()->post('studentNum');
        $username = Request::instance()->post('username');
        $password = Request::instance()->post('password');
        $seatId = Request::instance()->param('seatId');
        $studentId = session('studentId');

        // 第一次登陆需要根据传入的学号和姓名判断是否存在该学生
        if (is_null($studentId)) {
            if (is_null($studentNum)) {
                return $this->error('请注册后再登陆', url('firstWx?seatId=' . $seatId));
            }
            $que = array(
                'name' => $studentName,
                'num' => $studentNum
            );
            $Student = Student::get($que);
            if (is_null($Student)) {
                return $this->error('注册失败，请检查你的学号姓名是否正确', url('firstWx?seatId=' . $seatId));
            } else {
                // 对该学生的用户名密码进行赋值
                $Student->username = $username;
                $Student->password = $password;
                if(!$Student->save()) {
                return $this-error('用户名密码信息记录失败', url('firstWx?seatId=' . $seatId));
                } else {
                    session('studentId', $Student->getData('id'));
                }
            } 
        } else {
            $Student = Student::get($studentId);
        }

        // 登陆成功
        return $this->success('login success', url('Seat/sign?studentId=' . $Student->id . '&seatId=' . $seatId));
    }

    public function test123($key, $value = null )
    {
        if(is_null($value))
        {

        }
        else {

        }
    }
    public function test()
    {
        var_dump(input('post.'));
    }

     public function logOut()
    {
        if (Teacher::logOut()) {
            return $this->success('logout success', url('index'));
        } else {
            return $this->error('logout error', url('index'));
        }
    }

    /**
     * 负责学生端登陆后的显示
     */
    public function afterSign() {
        // 获取学生id，并将学生对象实例化
        $studentId = Request::instance()->param('studentId');
        $seatId = Request::instance()->param('seatId');
        $Student = Student::get($studentId);

        // 如果座位id接收值非空，说明正常扫码上课成功
        if (!is_null($seatId)) {
            // 实例化座位对象
            $Seat = Seat::get($seatId);
            // 通过座位id获取对应的教室信息
            $Classroom = Classroom::get($Seat->classroom_id);
            // 通过教室和教室的上课开始时间确定classCourse的id
            $classCourse = classCourse::get(['begin_time' => $Classroom->begin_time,
                'classroom_id' => $Classroom->id ] 
            );
        }

        // 通过中间表和学生id，获取该学生所上的课程
        $que = array(
            'student_id' => $studentId,
        );
        $pageSize = 2;
        $classDetails = ClassDetail::where($que)->paginate($pageSize);

        // 将数据传入V层进行渲染
        $this->assign('classDetails', $classDetails);
        return $this->fetch();
    }

    /**
     * 清空上一个老师与教室的绑定信息
     * @param classroomId 扫码对应的教室id
     */
    public function clearContact($classroomId) {
        // 判断是否存在classroom_id字段为该教室的教师对象，如果存在删除上一个教师的信息
        $Teacher = Teacher::get(['classroom_id' => $classroomId]);
        if (!is_null($Teacher)) {
            $Teacher->classroom_id = 0;
            if(!$Teacher->save()) {
                return $this->error('上一个教师与教室信息解除失败,请重新上课', url('Course/index'));
            }
        }
    }
}