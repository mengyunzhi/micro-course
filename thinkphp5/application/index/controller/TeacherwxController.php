<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Classroom;
use app\common\model\Teacher;
use app\common\model\InClass;
use app\common\model\Seat;
use app\common\model\ClassCourse;
use app\common\model\ClassDetail;
use app\common\model\Grade;
use app\common\model\Gradeaod;

/**
 * 负责微信端教师界面的显示
 */
class TeacherwxController extends IndexController {
    /**
     * 微信端的显示主页，显示课程
     */
    public function index() {
        // 获取教师id，并根据教师id获取教室id
        $teacherId = session('teacherId');
        $Teacher = Teacher::get($teacherId);
        $classroomId = $Teacher->classroom_id;
        $name = Request::instance()->param('name');

        // 获取该老师所教的所有课程
        // 分页页数为2
        $pageSize = 2;
        $courses = Course::where('teacher_id', '=', $teacherId);

        if (!empty($name)) {
            $courses = $courses->where('name', '=', $name)->paginate($pageSize);
            $this->assign('courses', $courses);
            return $this->fetch();
        } else {
            $courses = Course::where('teacher_id', '=', $teacherId)->paginate($pageSize);
            // 将课程对象数组传入V层进行渲染
            $this->assign('courses', $courses);

            return $this->fetch();
        }
    }
    /**
     * 微信端扫码登陆后的跳转到上课表现成绩加减分部分
     */
    public function wxAod() {
        // 接收教室id和教师id,并实例化教室对象和教师对象
        $teacherId = session('teacherId');
        $Teacher = Teacher::get($teacherId);
        $classroomId = $Teacher->classroom_id;
        $Classroom = Classroom::get($classroomId);
        if ($Classroom->course_id === 0 || is_null($Classroom->course_id)) {
            return $this->error('请先开始上课', Request::instance()->header('referer'));
        }

        // 根据教室获取课程id
        $courseId = $Classroom->course_id;
        $Course = Course::get($courseId);

        // 通过教室获取当前正在上课的学生名单(因为学生扫码有判断是否为本班学生，故不必判断)
        // 定义每页两项数据
        $pageSize = 2;
        $que = array(
            'classroom_id' => $classroomId,
             'is_seated' => '1'
            );
        $seats = Seat::where($que);

        // 获取查询条件学生学号num
        $num = Request::instance()->param('name');
        if (!is_null($num)) {
            $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$courseId);
            $courseStudents = $courseStudents->join('student s','a.student_id = s.id')->where('s.num','=',$num)->select();
            if (!empty($courseStudents)) {
                $seats = $seats->where('student_id', '=', $courseStudents[0]->student_id)->paginate($pageSize);
            } else {
                return $this->error('查找不存在', Request::instance()->header('referer'));
            }
        } else {
            $seats = Seat::where($que)->paginate($pageSize);
        }

        $this->assign('seats', $seats);
        $this->assign('Teacher', $Teacher);
        $this->assign('Course', $Course);

        return $this->fetch();
    }   

    /**
     * 负责微信的加减分项选择
     */
    public function gradeChange() {
        // 接收座位id和课程id和加减分判断aodStateId，并进行实例化
        $seatId = Request::instance()->param('seatId/d');
        $courseId = Request::instance()->param('courseId/d');
        $aodState = Request::instance()->param('aodState/d');
        $Seat = Seat::get($seatId);
        $Course = Course::get($courseId);
        if (is_null($Seat)) {
            return $this->error('学生信息不存在', url('wxAod?teacherId=' . $Course->Teacher->id));
        }

        // 通过课程id和学生id获取成绩对象
        $Grade = Grade::get(['course_id' => $Course->id, 'student_id' => $Seat->student->id]);
        if (is_null($Grade)) {
            return $this->error('学生信息不存在', url('wxAod?teacherId=' . $Course->Teacher->id));
        }

        // 通过加减分判断和课程id获取对应存在的加减分项
        $gradeAods = Gradeaod::where(['aod_state' => $aodState, 'course_id' => $courseId])->select();

        $this->assign('Grade', $Grade);
        $this->assign('gradeAods', $gradeAods);
        $this->assign('Student', $Seat->student);

        return $this->fetch();
    }

    /**
     * 微信端的加减分保存操作
     */
    public function update() {
        // 接收教师id
        $teacherId = session('teacherId');
        $Teacher = Teacher::get($teacherId);
        // 获取教室id，并对教室进行实例化
        $classroomId = $Teacher->classroom_id;
        $Classroom = Classroom::get($classroomId);

        // 根据教室对象获取对应的ClassCourse对象
        $ClassCourse = ClassCourse::get(['classroom_id' => $Classroom->id, 'begin_time' => $Classroom->begin_time]);
        if (is_null($ClassCourse)) {
            return $this->error('上课课程查找失败,请先开始上课', Request::instance()->header('referer'));
        }

        // 接收加减分项id和对应的成绩id
        // 实例化对象请求
        $Request = Request::instance();
        $gradeId = Request::instance()->param('gradeId/d');
        $aodNum = Request::instance()->param('aodNum/d');

        // 实例化成绩对象和加减分对象
        $Grade = Grade::get($gradeId);

        // 加减分操作并保存更改后的成绩
        $Grade->coursegrade += $aodNum;
        if (!$Grade->save()) {
            return $this->error('成绩修改失败，请重新操作' . $Grade->getError());
        } 
        // 根据上课课程获取上课详情对象,并进行修改
        $que = array(
            'class_course_id' => $ClassCourse->id,
            'student_id' => $Grade->Student->id
        );
        $ClassDetail = ClassDetail::get($que);
        $ClassDetail->aod_num += $aodNum;
        if (!$ClassDetail->save()) {
            return $this->error('上课详情更改失败', Request::instance()->header('referer'));
        }

        return $this->success('成绩修改成功', url('wxAod'));
    }

    /**
     * 签到修改方法
     */
    public function signChange() {
        // 直接从session获取教师id:teacherId,并实例化教师对象
        $teacherId = session('teacherId');
        $Teacher = Teacher::get($teacherId);

        // 将教师数据库字段classroom_id 赋值给变量$classroomId，并对教室对象实例化
        $classroomId = $Teacher->classroom_id;
        $Classroom = Classroom::get($classroomId);
        if ($Classroom->course_id === 0 || is_null($Classroom->course_id)) {
            return $this->error('请先开始上课', Request::instance()->header('referer'));
        }

        // 通过get方法，利用教室对象的begin_time和course_id字段获取上课课程对象ClassCourse(别用select方法，就用get)
        $ClassCourse = ClassCourse::get(['begin_time' => $Classroom->begin_time,
            'course_id' => $Classroom->course_id
        ]);
        // 获取到上课课程对象后增加判断是否为空，如果为空则返回提示:请先开始上课，并返回上一级
        if (is_null($ClassCourse)) {
            return $this->error('上课课程查找失败,请先开始上课', Request::instance()->header('referer'));
        }

        // 根据上课课程对象获取上课详情对象数组classDetails，外键为class_course_id
        // 每页显示两项数据
        $pageSize = 2;
        $classDetails = ClassDetail::where('class_course_id', '=', $ClassCourse->id)->select();

        // 获取本班的所有学生
        $courseStudents = CourseStudent::where('course_id', '=', $Classroom->course_id)->select();

        // 获取未签到的学生
        $unSigns = [];
        $this->unSignStudents($classDetails, $courseStudents, $unSigns);
        $classDetails = ClassDetail::where('class_course_id', '=', $ClassCourse->id)->paginate($pageSize);

        // 将上课详情对象数组传入V层
        $this->assign('classDetails', $classDetails);
        $this->assign('unSigns', $unSigns);
        $this->assign('Course', $Classroom->course);
        $this->assign('ClassCourse', $ClassCourse);

        // 返回渲染后的效果
        return $this->fetch();
    }

    /**
     * 签到更改方法
     */
    public function signUpdate() {
        // 接收对应的学生id和上课课程id
        $studentId = Request::instance()->param('studentId');
        $classCourseId = Request::instance()->param('classCourseId');

        // 实例化上课课程对象，并判断是否为空
        $ClassCourse = ClassCourse::get($classCourseId);
        if (is_null($ClassCourse)) {
            return $this->error('上课课程接收失败，请重新更改', Request::instance()->header('referer'));
        }

        // 新建上课详情对象，并进行赋值和保存
        $ClassDetail = new ClassDetail();
        if (!$this->saveDetail($ClassDetail, $studentId, $classCourseId)) {
            return $this->error('上课签到状态修改失败，请重新修改', Request::instance()->header('referer'));
        }
        return $this->success('签到状态修改成功', url('signChange'));
    }

    /**
     * 获取未签到的学生
     * @param classDetails 上课详情对象数组
     * @param courseStudents 课程学生中间表对象数组
     * @param unSigns 用于存储的学生对象数组
     */
    public function unSignStudents($classDetails, $courseStudents, &$unSigns) {
        // 根据上课详情对象数组和课程学生对象数组获取未签学生对象数组
        $number = sizeof($classDetails);
        $numberOne = sizeof($courseStudents);
        
        $count = 0;
        for ($i = 0; $i < $numberOne; $i ++) {
            $flag = 1;
            for($j = 0; $j < $number; $j ++) {
                if ($courseStudents[$i]->student == $classDetails[$j]->student) {
                    $flag = 0;
                }
            }
            if ($flag === 1) {
                $unSigns[$count++] = $courseStudents[$i]->student;
            }
        }
    }

    /**
     * 上课详情对象赋值保存
     * @param ClassDetail 待修改的上课详情对象
     * @param studentId 学生对象id
     * @param classCourseId 上课课程对象id
     */
    public function saveDetail(&$ClassDetail, $studentId, $classCourseId) {
        // 对传入的上课详情对象进行赋值
        $ClassDetail->student_id = $studentId;
        $ClassDetail->class_course_id = $classCourseId;
        // 对于补签学生默认上课座位id是-1
        $ClassDetail->seat_id = -1;
        $ClassDetail->aod_num = 0;

        // 对新增后的上课详情对象进行保存
        return $ClassDetail->save();
    }

    /**
     * 对补签学生签到次数进行加一
     * @param studentId 学生对象ID
     * @param courseId 上课课程id
     */
    public function changeGrade($studentId, $courseId) {
        // 根据学生id和课程id获取该学生对应的成绩对象
        $Grade = Grade::get(['student_id' => $studentId, 'course_id' => $courseId]);

        // 签到次数加一，重新计算签到成绩和总成绩并保存
        $Grade->resigternum += 1;
        $Grade->getAllgrade();
    }

    /**
     * 微信端增加密码修改
     */
    public function changePassword() {
        // 接收教师id并对教师进行实例化
        $teacherId = session('teacherId');
        if (is_null($Teacher = Teacher::get($teacherId))) {
            return $this->error('老师信息不存在', Request::instance()->header('referer'));
        }

        // 获取原密码、新密码、确认密码
        $oldPassword = Request::instance()->param('oldPassword');
        $newPassword = Request::instance()->param('newPassword');
        $newPasswordAgain = Request::instance()->param('newPasswordAgain');

        $this->assign('Teacher', $Teacher);
        $this->assign('oldPassword', $oldPassword);
        $this->assign('newPassword', $newPassword);
        $this->assign('newPasswordAgain', $newPasswordAgain);
        
        return $this->fetch();
    }

    /**
     * 教师微信端密码更新
     */
    public function passwordUpdate() {
        // 接收教师id并对教师进行实例化
        $teacherId = session('teacherId');

        // 接收旧密码和新密码
        $oldPassword = Request::instance()->param('oldPassword');
        $newPassword = Request::instance()->param('newPassword');
        $newPasswordAgain = Request::instance()->param('newPasswordAgain');
        if (is_null($Teacher = Teacher::get($teacherId))) {
            return $this->error('学生信息接收失败', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&newPasswordAgain=' . $newPasswordAgain));
        }

        // 首先判断输入的原密码是否正确
        if ($Teacher->password !== $Teacher->encryptPassword($oldPassword)) {
            return $this->error('修改失败,原密码不正确', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&newPasswordAgain=' . $newPasswordAgain));
        } else {
            // 原密码输入正确时
            if ($newPasswordAgain === $newPassword) {
                $Teacher->password = $Teacher->encryptPassword($newPassword);
                // 如果新密码长度不符合要求，返回重新修改
                if (20 < strlen($newPassword) || strlen($newPassword) < 6) {
                    return $this->error('密码长度限制:6至20位', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&newPasswordAgain=' . $newPasswordAgain));
                }
                // 如果密码符合格式要求，下面保存
                if (!$Teacher->save()) {
                    return $this->error('新密码保存失败,请重新修改', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&newPasswordAgain=' . $newPasswordAgain));
                } else {
                    session('teacherId',null);
                    return $this->success('密码修改成功,请重新登陆', url('Login/teacherIndex?username=' . $Teacher->username));
                }
            } else {
                return $this->error('两次密码不相同，请确认新密码一致', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&newPasswordAgain=' . $newPasswordAgain));
            }
        }
    }
}