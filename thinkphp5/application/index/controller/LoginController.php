<?php
namespace app\index\controller;
use think\Controller;
use think\Request;     //请求
use app\common\model\Teacher; //教师模型
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\ClassDetail;
use app\common\model\Grade;

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
        $postData = Request::instance()->post();
        
        // 直接调用M层方法，进行登录。
        if (Teacher::login($postData['username'], $postData['password'])) {
            if($postData['username']=='admin'){

                return $this->success('login success', url('Term/index'));
            }
            return $this->success('login success', url('Course/index'));
        } else {
            return $this->error('username or password incorrent', url('index'));
        }
    }

    /**
     * 负责第一次微信登陆的注册任务
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
     * 微信登陆
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
            } else if(!$Student->save()) {
                return $this-error('用户名密码信息记录失败', url('firstWx?seatId=' . $seatId));
            } else {
                session('studentId', $Student->getData('id'));
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
        $courseId = Request::instance()->param('courseId');
        $Student = Student::get($studentId);

        // 通过中间表和学生id，获取该学生所上的课程
        $que = array(
            'student_id' => $studentId,
            'class_course_id' => $courseId
        );
        $pageSize = 2;
        $classDetails = ClassDetail::where($que)->paginate($pageSize);

        // 将数据传入V层进行渲染
        $this->assign('classDetails', $classDetails);

        return $this->fetch();
    }
}