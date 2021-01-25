<?php
namespace app\index\controller;
use think\Request;     //请求
use think\Controller;
use app\common\model\Teacher; //教师模型
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\ClassDetail;
use app\common\model\Grade;
use app\common\model\Seat;
use app\common\model\Classroom;
use app\common\model\ClassCourse;

/**
 * 负责教师和学生扫码登陆验证
 */
class LoginController extends Controller {
    //用户登录表单
    public function index() {
        // 获取教师id
        $teacherId = session('teacherId');
        if (!is_null($teacherId)) {
            // 获取对应教师
            $Teacher = Teacher::get($teacherId);
            if (!is_null($Teacher)) {
                if ($Teacher->username === 'admin') {
                    // 如果登陆了已经，则直接跳转到管理员首页
                    $url = url('index/term/index');
                    header("Location: $url");
                    exit();
                } else {
                    // 如果不是管理员，则跳转到教师首页
                    $url = url('index/course/index');
                    header("Location: $url");
                    exit();
                }
            }
        }
        // 接收登陆信息
        $username = Request::instance()->param('username');
        $password = '';

        $this->assign('username', $username);
        $this->assign('password', $password);

        // 显示登录表单
        return $this->fetch();
    }
    /**
     * web端处理登陆提交的信息
     */
    public function login() {
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
        $classroomId = Request::instance()->param('classroomId/d');
        if (is_null($classroomId) || $classroomId === 0) {
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
                $this->checkPassword($password, $username, $name);
                if (strlen($username) > 25 || strlen($username) < 6) {
                    return $this->error(
                        '注册失败,请保证用户名在6-25位之间',
                        url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password)
                    );
                } else {
                    $Teacher = new Teacher();
                    $Teacher->name = $name;
                    $Teacher->username = $username;
                    $Teacher->classroom_id = $classroomId;
                    $Teacher->password = $Teacher->encryptPassword($password);
                    if (!$Teacher->validate()->save()) {
                        return $this->error(
                            '注册失败,请保证教师姓名长度2-4位',
                            url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
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
     * 学生注册首页
     */
    public function studentFirst()
    {
        // 接受传入的几项信息
        $username = Request::instance()->param('username');
        $name = Request::instance()->param('name');
        $seatId = Request::instance()->param('seatId');

        // 将接受的信息传入V层，防止第一次注册失败，重新注册情况
        $this->assign('username', $username);
        $this->assign('name', $name);
        $this->assign('seatId', $seatId);

        return $this->fetch();
    }

    /**
     * 负责学生注册判定
     */
    public function checkStudent()
    {
        // 接受姓名/学号（用户名）/密码
        $username = Request::instance()->param('username');
        $name = Request::instance()->param('name');
        $password = Request::instance()->param('password');
        $seatId = Request::instance()->param('seatId');
        if (is_null($seatId)) {
            return $this->error(
                '座位信息传递失败',
                url('studentFirst?username=' . $username . '&name=' . $name));
        }

        // 首先判断学号长度是否为6位
        if (strlen($username) !== 6) {
            return $this->error(
                '请确认学号输入正确',
                url('studentFirst?username=' . $username . '&name=' . $name));
        }

        // 判断姓名长度
        if (strlen($name) > 25) {
            return $this->error(
                '请确认姓名输入正确',
                url('studentFirst?username=' . $username . '&name=' . $name));
        }

        // 密码长度和组成判定
        // 判断密码长度是否在六位到25位之间
        if (strlen($password) < 6 || strlen($password) > 25) {
            return $this->error(
                '请保证密码长度在6位到25位之间',
                url('studentFirst?username=' . $username . '&name=' . $name));
        }

        // 判断密码是否含有字母
        if (!preg_match('/[a-zA-Z]/', $password)) {
            return $this->error(
                '请保证密码中包含字母',
                url('studentFirst?username=' . $username . '&name=' . $name));
        }

        // 均符合条件后判断数据库中是否有该学生信息
        $que = array(
            'name' => $name,
            'username' => $username
        );
        $StudentTmp = Student::get($que);

        // 如果数据库中有该信息，则判断该条信息是否有密码
        if (!is_null($StudentTmp)) {
            // 如果有密码，则说明该条信息已经被注册
            if (!is_null($StudentTmp->password)) {
                return $this->error('该学生信息已被注册,如非本人，请联系管理员修改');
            // 如果没有密码，说明该条信息未被注册，只是被老师导入了而已
            } else {
                $StudentTmp->password = $StudentTmp->encryptPassword($password);
                if (!$StudentTmp->validate()->save()) {
                    return $this->error(
                        '注册失败',
                        Request::instance()->header('referer'));
                }
            }
        // 第二种情况：数据库没有该条信息，则需要新建，直接注册
        } else {
            $Student = new Student();
            $Student->name = $name;
            $Student->username = $username;
            $Student->password = $Student->encryptPassword($password);
            $Student->num = $username;
            // 将新建立的学生信息保存
            if (!$Student->validate()->save()) {
                return $this->error(
                    '保存失败',
                    Request::instance()->header('referer'));
            }
        }

        return $this->success('注册成功', url('studentWx?seatId=' . $seatId));
    }

    /**
     * 老师注册负责判断密码是否符合要求
     * @param password 待判断的密码
     * @param username 不符合规则跳转需要传的用户名
     * @param name 不符合规则需要传递的注册姓名
     */
    public function checkPassword($password, $username, $name)
    {
        // 判断密码长度是否在六位到25位之间
        if (strlen($password) < 6 || strlen($password) > 25) {
            return $this->error(
                '请保证密码长度在6位到25位之间',
                url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
        }

        // 判断密码是否含有字母
        if (!preg_match('/[a-zA-Z]/', $password)) {
            return $this->error(
                '请保证密码中包含字母',
                url('teacherFirst?name=' . $name . '&username=' . $username . '&password=' . $password));
        }
    }

    /**
     * 学生登陆首页
     */
    public function studentWx() {
        // 获取从wxLogin传出的seatId
        $seatId = Request::instance()->param('seatId');
        if (is_null($seatId)) {
            return $this->error('座位信息传递失败,请重新扫码', '');
        }
        // 首先判断当前学生是否session未过期,如果未过期，直接重定向到登录判定界面
        $studentId = session('studentId');
        if (!is_null($studentId)) {
            $url = url('index/login/wxLogin?seatId=' . $seatId);
            header("Location: $url");
            exit();
        }

        // 接收上次登陆失败返回的信息
        $username = Request::instance()->param('username');
        $password = '';

        // 将$seatId传入V层
        $this->assign('password', $password);
        $this->assign('username', $username);
        $this->assign('seatId', $seatId);
        // 直接到V层渲染
        return $this->fetch();
    }

    /**
     * 多条相同学号学生登录
     */
    public function studentAgain()
    {
        // 获取从wxLogin传出的seatId
        $seatId = Request::instance()->param('seatId');
        if (is_null($seatId)) {
            return $this->error(
                '座位信息传递失败,请重新扫码',
                Request::instance()->header('referer'));
        }
        // 首先判断当前学生是否session未过期,如果未过期，直接重定向到登录判定界面
        $studentId = session('studentId');
        if (!is_null($studentId)) {
            $url = url('index/login/wxLogin?seatId=' . $seatId);
            header("Location: $url");
            exit();
        }

        // 获取当前所在的控制器
        $action = 'studentAgain';

        // 接收上次登陆失败返回的信息
        $username = Request::instance()->param('username');
        $name = Request::instance()->param('name');
        $password = '';

        // 将$seatId传入V层
        $this->assign('password', $password);
        $this->assign('username', $username);
        $this->assign('name', $name);
        $this->assign('action', $action);
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
        $seatId = Request::instance()->param('seatId/d');
        $name = Request::instance()->param('name');
        $action = Request::instance()->param('action');

        // 获取学生id，判断session是否过期
        $studentId = session('studentId');
        $Student = Student::get($studentId);
        /*dump($studentId);
        dump($Student);
        die();
        */
        // 首先判断是不是没登录或登录信息过期且存在多个相同学号情况
        if (is_null($Student) || is_null($studentId)) {
            // 首先根据学号判断是否有多个为当前学号的
            $students = Student::where('username', '=', $username)->select();
            if (sizeof($students) > 1 && is_null($action)) {
                return $this->success(
                    '检测到其他学号相同注册信息，请填写完整信息',
                    url('studentagain?username=' . $username . '&seatId=' . $seatId));
            }
            if (sizeof($students) > 1) {
                // 如果是从studentAgain跳过来的直接登录
                if ($action === 'studentAgain') {
                    // 此种情况需要通过name和用户名和密码共同判断学生信息
                    if (Student::login($username, $password, $name)) {
                        // 登录成功，直接跳转到签到页面
                        $studentId = session('studentId');
                        return $this->success(
                            '登陆成功',
                            url('Seat/sign?studentId=' . $studentId . '&seatId=' . $seatId));
                    }
                } else {
                    return $this->error(
                        '登录信息不正确',
                        url('studentagain?username=' . $username . '&seatId=' . $seatId . '&name=' . $name));
                }
            }
        }

        // 第2种session已经过期，输入用户名密码登陆
        if (is_null($Student) || is_null($studentId)) {
            if (is_null($username) || is_null($password)) {
                return $this->error(
                    '请先输入完整的登陆信息',
                    url('studentwx?username=' . $username . '&password=' . $password . '&seatId=' . $seatId));
            } else {
                if (Student::login($username, $password)) {
                    // 登陆成功
                    $Student = Student::get($studentId = session('studentId'));
                    // 首先判断座位id是否接收成功,如果没成功即为修改密码情况
                    if (is_null($seatId) || $seatId === 0) {
                        return $this->error(
                            '座位信息不存在，请重新扫码',
                            url('studentwx?username=' . $username . '&password=' . $password));
                    }
                    return $this->success(
                        '登陆成功',
                        url('Seat/sign?studentId=' . $Student->id . '&seatId=' . $seatId));
                } else {
                    if ($action !== 'studentAgain') {
                        return $this->error(
                            '用户名或密码不正确',
                            url('studentwx?username=' . $username . '&password=' . $password . '&seatId=' . $seatId));
                    } else {
                        return $this->error(
                            '用户名或密码不正确',
                            url('studentAgain?username=' . $username . '&name=' . $name . '&seatId=' . $seatId));
                    }
                }
            }

            // 第二种session未过期，直接登陆
        } else {
         // 首先判断座位id是否接收成功,如果没成功即为修改密码情况
            if (is_null($seatId) || $seatId === 0) {
                return $this->error(
                    '座位信息不存在，请重新扫码',
                    url('studentwx?username=' . $username . '&password=' . $password));
            }
            return $this->success(
                '登陆成功',
                url('Seat/sign?studentId=' . $Student->id . '&seatId=' . $seatId));
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
        if (is_null($Classroom)) {
            $this->error(
                '当前教室信息为空，请重新扫码',
                Request::instance()->header('referer'));
        }
        $teacherId = session('teacherId');

        // 判断是否为同一老师在上课时间再次登录，如果不是则清除教室信息和教师绑定
        if (!is_null($Teacher)) {
            if ($Teacher->id !== $teacherId || $Classroom->out_time < time()) {
                if (!is_null($Teacher)) {
                    $Teacher->classroom_id = 0;
                    if(!$Teacher->save()) {
                        return $this->error(
                            '教师与教室信息解除失败,请重新上课',
                            Request::instance()->header('referer'));
                    }
                    // 教室信息重置
                    if (!$this->clearClassroom($Classroom)) {
                        return $this->error(
                            '教室信息修改失败',
                            Request::instance()->header('referer'));
                    }
                }
            }  
        }
    }

    /**
    * 清除教室中保留的上节课信息
    * @param $Classroom 被清除教室对象
    */
    protected function clearClassroom(&$Classroom) {
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
     * 老师微信端登陆
     */
    public function teacherIndex() {
        // 首先获取教师id，判断session是否过期
        $teacherId = session('teacherId');

        $classroomId = Request::instance()->param('classroomId');
        $Teacher = Teacher::get($teacherId);

        // 如果session还没有过期的情况下，直接登陆
        if (!is_null($Teacher) && !is_null($teacherId)) {
            // 绑定教师信息和教室信息
            $Teacher->classroom_id = $classroomId;
            if (!$Teacher->save()) {
                return $this->error(
                    '教师-教室信息绑定失败',
                    Request::instance()->header('referer'));
                }
                return $this->success('登陆成功', url('teacherwx/index'));
            }

        // 接收用户名和密码,避免二次登陆重新输入账号密码
        $username = Request::instance()->param('username');
        $password = '';
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
                if (is_null($Teacher || is_null($teacherId))) {
                    return $this->error('教师信息不存在', url('teacherFirst?classroomId=' . $classroomId));
                } else {
                    // 绑定教师和教室信息
                    $Teacher->classroom_id = $classroomId;
                    if (!$Teacher->save()) {
                        return $this->error(
                            '教室-老师信息绑定失败',
                            Request::instance()->header('referer'));
                    }
                }
                // 登陆成功后也保存成功教室信息
                return $this->success('登陆成功', url('teacherwx/index'));
            } else {
                // 登陆不成功状况
                return $this->error(
                    '用户名或密码不正确',
                    url('teacherIndex?username=' . $username . '&password=' . $password));
            }
        } else {
            // 用户名密码输入不完整状况，重新输入
            return $this->error('请输入完整的信息', Request::instance()->header('referer'));
        }
    }

    /**
     * web端教师登出
     */
    public function logOut() {
        if (Teacher::logOut()) {
            return $this->success('注销成功', url('index'));
        } else {
            return $this->error(
                '注销失败',
                Request::instance()->header('referer'));
        }
    } 

    /**
     * 微信端教师登出
     */
    public function wxLogOut() {
        if (Teacher::logOut()) {
            return $this->success('注销成功', url('teacherIndex'));
        } else {
            return $this->error('注销失败', Request::instance()->header('referer'));
        }
    }
}