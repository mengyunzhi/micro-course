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
    public function index() {
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
            return $this->success('登陆成功', url('Course/index'));
        } else {
            return $this->error('用户名或密码不正确', url('index?username=' . $username . '&password=' . $password));
        }
    }

    /**
     * 负责教师端微信注册
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

        // 首先判断用户名密码是否输入完整，如果不完整重新输入信息
        if (is_null($username) || is_null($password) || is_null($name)) {
            return $this->error('请输入完整注册信息', url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
        } else {
            // 保存教师信息
            if (!is_null($Teacher = Teacher::get(['username' => $username]))) {
                return $this->error('用户名已存在', url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
            } else {
                if (strlen($password) > 25 || strlen($password) < 6) {
                    return $this->error('注册失败,请保证密码在6-25位之间', url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
                } else {
                    if (strlen($username) > 25 || strlen($username) < 6) {
                        return $this->error('注册失败,请保证用户名在6-25位之间', url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
                    } else {
                        $Teacher = new Teacher();
                        $Teacher->name = $name;
                        $Teacher->username = $username;
                        $Teacher->num = 0;
                        $Teacher->classroom_id = $classroomId;
                        $Teacher->password = $Teacher->encryptPassword($password);
                        if (!$Teacher->validate()->save()) {
                            return $this->error('注册失败,请保证教师姓名长度2-4位', url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
                        } 
                    }
                }
            }
            
            // 调用M层的方法对用户名密码进行判断，同时存储登陆的session
            if (Teacher::login($username, $password)) {
                // 如果不是则认定为教师端登陆，跳转到教师端
                // 如果登陆成功后，实例化教师对象，并修改教师classroom_id属性
                // 首先清除上一个教师和教室的绑定
                $this->clearContact($classroomId);
                return $this->success('注册成功', url('Teacherwx/index'));
            } 
        }
    }

    /**
     * 负责老师的第一次登陆注册
     */
    public function teacherFirst() {
        // 获取教室id
        $classroomId = Request::instance()->param('classroomId');

        // 接收教师姓名、用户名、密码
        $name = Request::instance()->param('name');
        $username = Request::instance()->param('username');
        $password = Request::instance()->param('password');

        // 将三种信息传入V层防止第一次信息输入不完整重新输入
        $this->assign('name', $name);
        $this->assign('username', $username);
        $this->assign('password', $password);

        // 将教室id传入v层s
        $this->assign('classroomId', $classroomId);
        return $this->fetch();
    }

    /**
     * 学生登陆首页
     */
    public function studentWx() {
        // 接收上次登陆失败返回的信息
        $username = Request::instance()->param('username');
        $password = Request::instance()->param('password');

        // 获取从wxLogin传出的seatId
        $seatId = Request::instance()->param('seatId');

        // 将$seatId传入V层
        $this->assign('password', $password);
        $this->assign('username', $username);
        $this->assign('seatId', $seatId);
        // 直接到V层渲染
        return $this->fetch();
    }
    
    /**
     * 学生登陆
     */
    public function wxLogin() {
        // 接收post信息,并获取学生id
        $username = Request::instance()->post('username');
        $password = Request::instance()->post('password');
        $seatId = Request::instance()->param('seatId');

        // 获取学生id，判断session是否过期
        $studentId = session('studentId');
        // 第一种session已经过期，输入用户名密码登陆
        if (is_null($studentId)) {
            if (is_null($username) || is_null($password)) {
                return $this->error('请先输入完整的登陆信息', url('studentwx?username=' . $username . '&password=' . $password));
            } else {
                if (Student::Login($username, $password)) {
                    // 登陆成功
                    $Student = Student::get($studentId = session('studentId'));
                    // 首先判断座位id是否接收成功,如果没成功即为修改密码情况
                    if (is_null($seatId)) {
                        return $this->error('登陆成功', url('Student/aftersign?studentId' . $St));
                    }
                    return $this->success('登陆成功', url('Seat/sign?studentId=' . $Student->id . '&seatId=' . $seatId));
                } else {
                    return $this->error('用户名或密码不正确', url('studentwx?username=' . $username . '&password=' . $password));
                }
            }

            // 第二种session未过期，直接登陆
        } else {
            $Student = Student::get($studentId);
            // 首先判断座位id是否接收成功,如果没成功即为修改密码情况
            if (is_null($seatId)) {
                return $this->error('登陆成功', url('Student/aftersign?studentId' . $Student->id));
            }
            return $this->success('登陆成功', url('Seat/sign?studentId=' . $Student->id . '&seatId=' . $seatId));
        }
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
            return $this->success('注销成功', url('index'));
        } else {
            return $this->error('注销失败', url('index'));
        }
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
     * 老师微信端登陆方法
     */
    public function teacherIndex() {
        // 首先获取教师id，判断session是否过期
        $teacherId = session('teacherId');
        $classroomId = Request::instance()->param('classroomId');

        // 如果session还没有过期的情况下，直接登陆
        if (!is_null($teacherId)) {
            // 绑定教师信息和教室信息
            $Teacher = Teacher::get($teacherId);
            if (is_null($Teacher)) {
                return $this->error('教师信息不存在', url('teacherLogin?class'));
            } else {
                $Teacher->classroom_id = $classroomId;
                if (!$Teacher->save()) {
                    return $this->error('教师-教室信息绑定失败', Request::instance()->header('referer'));
                }
            }
            return $this->success('登陆成功', url('teacherwx/index'));
        }

        // 接收用户名和密码,避免二次登陆重新输入账号密码
        $password = Request::instance()->param('password');
        $username = Request::instance()->param('username');
        $classroomId = Request::instance()->param('classroomId');

        $this->assign('username', $username);
        $this->assign('classroomId', $classroomId);
        $this->assign('password', $password);

        // 调用index模板
        return $this->fetch();
    }

    /**
     * 老师微信登陆判断
     */
    public function teacherLogin() {
        // session如果已经过期状况
        // 接收用户名和密码
        $password = Request::instance()->param('password');
        $username = Request::instance()->param('username');
        $classroomId = Request::instance()->param('classroomId');

        // 通过判断用户名密码是否为空来区分登陆和密码不正确重新登陆状况
        if (!is_null($username) && !is_null($password)) {
            // 直接调用M层方法，进行登录。
            if (Teacher::login($username, $password)) {
                // 如果不是则认定为教师端登陆，跳转到教师端
                // 获取教师id
                $teacherId = session('teacherId');
                $Teacher = Teacher::get($teacherId);
                if (!is_null($Teacher)) {
                    return $this->error('教师信息不存在', url('teacherFirst?classroomId=' . $classroomId));
                } else {
                    // 绑定教师和教室信息
                    $Teacher->classroom_id = $classroomId;
                    if (!$Teacher->save()) {
                        return $this->error('教室-老师信息绑定失败', Request::instance()->header('referer'));
                    }
                }
                // 登陆成功后也保存成功教室信息
                return $this->success('登陆成功', url('teacherwx/index'));
            } else {
                // 登陆不成功状况
                return $this->error('用户名或密码不正确', url('teacherIndex?username=' . $username . '&password=' . $password));
            }
        } else {
            // 用户名密码输入不完整状况，重新输入
            return $this->error('请输入完整的信息', Request::instance()->header('referer'));
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
            return $this->error('密码长度应为6到25之间', url('passwordModification?username=' . $username));
        }
        $Teacher->password = $Teacher->encryptPassword($password);
        if(!$Teacher->save()) {
            return $this->error('密码更新失败', url('passwordModification?username=' . $username));
        }

        return $this->success('密码修改成功,请重新登录', url('index?username=', $username));
    }
}