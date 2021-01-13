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
use think\validate;

/**
 * 负责教师和学生扫码登陆
 */
class LoginController extends Controller
    {    //用户登录表单
    public function index()
    {
        // 接收登陆信息
        $username = Request::instance()->param('username');
        $password = Request::instance()->param('password');

        $this->assign('username', $username);
        $this->assign('password', $password);

        // 显示登录表单
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
                return $this->success('登陆成功', url('Term/index'));
            }
            // 如果不是则认定为教师端登陆，跳转到教师端
            return $this->success('登录成功', url('Course/index'));
        } else {
            return $this->error('用户名或密码不正确', url('index'));
        }
    }

    /**
     * 负责教师端微信登陆，理论上与网页端不冲突
     */
    public function wxTeacher() {
        // 接收用户名密码信息和教室id
        $name = Request::instance()->post('name');
        $username = Request::instance()->post('username');
        $password = Request::instance()->post('password');
        $classroomId = Request::instance()->param('classroomId');
        if (is_null($classroomId)) {
            return $this->error('教室信息传递失败，请从新扫码', Request::instance()->header('referer'));
        }

        // 获取教师id，并判断是否存在teacherId;接收教室id,并将其存入session中
        $teacherId = session('teacherId');

        // 判断该老师是不是第一次登陆
        if (is_null($teacherId)) {
            // 首先判断用户名密码是否输入完整，如果不完整重新输入信息
            if (is_null($username) || is_null($password || is_null($name))) {
                return $this->error('请输入完整注册信息', url('teacherFirst?classroomId=' . $classroomId));
            } else {
                // 首先根据姓名、用户名和密码判断数据库中是否存在该老师
                $Teacher = Teacher::get(['name' => $name, 'username' => $username]);
                if (is_null($Teacher)) {
                    $Teacher = new Teacher();
                    $Teacher->name = $name;
                    $Teacher->username = $username;
                    $Teacher->password = $this::encryptPassword($password);
                    $Teacher->num = 0;
                    if(!$Teacher->save()) {
                        return $this->error('注册教师信息失败，请重新注册', Request::instance()->header('referer'));
                    }
                }
                
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
                        return $this->error('教室-老师信息绑定失败，请重新扫码', Request::instance()->header('referer'));
                    }
                    return $this->success('login success', url('Teacherwx/index'));
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
            return $this->success('login success', url('Teacherwx/index'));
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

    /**
     * 
     */

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
        $pageSize = 5;
        $classDetails = ClassDetail::order('update_time desc')->where($que)->paginate($pageSize);

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
        $Classroom = Classroom::get($classroomId);
        $teacherId = session('teacherId');

        // 判断是否为同一老师在上课时间再次登录，如果不是则清除教室信息和教师绑定
        if (!is_null($Teacher)) {
            if ($Teacher->id !== $teacherId || $Classroom->out_time < time()) {
                if (!is_null($Teacher)) {
                    $Teacher->classroom_id = 0;
                    if(!$Teacher->save()) {
                        return $this->error('教师与教室信息解除失败,请重新上课', Request::instance()->header('referer'));
                    }
                    // 教室信息重置
                    if (!$this->clearClassroom($Classroom)) {
                        return $this->error('教室信息修改失败', Request::instance()->header('referer'));
                    }
                }
            }  
        }
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
        $Classroom->sign_time = 20;
        $Classroom->sign_deadline_time = 0;
        $Classroom->update_time = time();
        $Classroom->out_time = 0;
        $Classroom->begin_time = 0;
        $Classroom->sign_begin_time = 0;

        // 更新并保存数据
        return $Classroom->validate(true)->save();
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
     * 教师密码修改
     */
    public function passwordModification() {
        $username = input('username');
        $this->assign('username', $username);
        return $this->fetch();
    }

    /**
     * 教师密码修改
     */
    public function tpm() {
        $oldPassword = input('post.oldPassword');
        $password = input('post.password');
        $username = input('post.username');
        $Teacher =  Teacher::get(['username' => $username]);
        dump('1');
        dump($Teacher);

        //判断用户名是否存在
        if(is_null($Teacher)) {
            return $this->error('用户名不存在', url('passwordModification'));
        }

        //判断旧密码是否正确
        if(!Teacher::login($username, $oldPassword)) {
           return $this->error('旧密码错误', url('passwordModification?username=' . $username));
        }

        //判断新旧密码是否一致
        if($oldPassword === $password) {
           return $this->error('新旧密码一致', url('passwordModification?username=' . $username));
        }

        // 判断新密码位数是否符合标准c
        if(strlen($password) < 6 || strlen($password)>25) {
            return $this->error('密码位数错误', url('passwordModification?username=' . $username));
        }
        $Teacher->password = $Teacher->encryptPassword($password);
        if(!$Teacher->save()) {
            dump('error');die();
            return $this->error('密码更新失败', url('passwordModification?username=' . $username));
        }
        dump('success');die();

        return $this->success('密码修改成功,请重新登录', url('index?username=', $username));
    }
}