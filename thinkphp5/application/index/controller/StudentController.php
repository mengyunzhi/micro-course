<?php
namespace app\index\controller;
use app\common\model\Student;
use app\common\model\Course;
use think\Request;
use app\index\controller\Login;
use think\Controller;
use app\common\model\Grade;
use app\common\model\Seat;
use app\common\model\Classroom;
use app\common\model\ClassDetail;
use app\common\model\ClassCourse;
use app\common\model\CourseStudent;
class StudentController extends Controller
{
    /**
     * 学生的首页展示方法，主要负责教师端展示所教课程对应的学生信息
     */
    public function index() {
        try {
            // 获取课程id和查询信息name
            $id = Request::instance()->param('id');
            $num = Request::instance()->param('name');
            $page = Request::instance()->param('page');
            
            //实例化课程,并增加判断是否为当前教师
            if (is_null($course = Course::get($id))) {
                return $this->error('课程信息不存在', Request::instance()->header('referer'));
            }

            // 调用checkPower方法判断权限
            $this->checkPower($id);

            // 定制查询信息
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$id);
                    $courseStudents = $courseStudents->
                    join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate();
                } else {
                    $courseStudents = CourseStudent::where('course_id', '=', $id)->paginate();
                }
              
            $count=0;
            $this->assign('page', $page);
            $this->assign('count', $count);
            $this->assign('courseStudents', $courseStudents);
            $this->assign('course', $course);

            // 取回打包后的数据
            $htmls = $this->fetch();

            // 将数据返回给用户
            return $htmls;

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }

    /**
     * 学生的新增方法，对应save保存方法
     */
	public function add() {
        //获取正确课程对应的ID
        $courseId = Request::instance()->param('id');
        if (is_null($courseId)) {
            return $this->error('未获取到正确的课程信息', Request::instance()->header('referer'));
        }
        $page = Request::instance()->param('page');
        $grade = new Grade();
        $grade->course_id = $courseId;

        //获取该ID对应的课程信息
        $course = Course::get($courseId);
		$Student=new Student;
		$Student->num='';
		$Student->name='';
        $Student->id = 0;
         
        $this->assign('page',$page);
        $this->assign('Course',$course);
        $this->assign('grade',$grade);
		$this->assign('Student',$Student);

		return $this->fetch('edit');
	}

    /**
     * 负责对学生信息进行更改
     */
	public function edit() {
        // 接收学生id和当前所在页数和课程id   
        $page = Request::instance()->param('page');
        $courseId=Request::instance()->param('course_id/d');
        $id = Request::instance()->param('id');

        // 对课程和学生进行实例化
        if (is_null($Course =Course::get($courseId))) {
            return $this->error('课程不存在', Request::instance()->header('referer'));
        }

		//判断是否存在为此id的记录
		if(is_null($Student = Student::get($id))) {
			return $this->error('未找到ID为' . $id . '的记录');
		}

        // 获取教师id，并增加权限处理
        $this->checkPower($courseId);

		//取出班级列表
		$this->assign('Student',$Student);
        $this->assign('page',$page);
        $this->assign('Course',$Course);
		return $this->fetch();
	}

    /**
     * 负责对edit编辑后的保存
     */
	public function update() {
        // 接收学生id和课程id和当前所在页数,并对学生进行实例化和
        $studentId = Request::instance()->post('id/d');
        $page=Request::instance()->post('page/d');
        $courseId = Request::instance()->post('courseid/d');
        if (is_null($Student = Student::get($studentId))) {
            return $this->error('不存在ID为' . $studentId . '的记录');
        }

        // 获取课程信息，并判断是否有权限
        $this->checkPower($courseId);

        // 中间表信息在更新之前要删除
        $CourseStudent = CourseStudent::get(['student_id' => $studentId, 'course_id' => $courseId]);
        // 获取成绩信息,更新成绩
        $Grade = Grade::get(['student_id' => $studentId, 'course_id' => $courseId]);
        if (is_null($CourseStudent)) {
            return $this->error('中间表信息不存在', Request::instance()->header('referer'));
        }
        if (is_null($Grade)) {
            return $this->error('成绩信息不存在', Request::instance()->header('referer'));
        }

        // 增加判断是否当前页数合规 
        if($page === 0) {
            $page = 1;
        }

        // 学生信息更新并保存
        if (!$this->saveStudent($Student, true, $CourseStudent, $Grade)) {
            return $this->error('操作失败' . $Student->getError());
        }

        // 成绩更新
        $this->success('操作成功', url('Student/index?id=' . $courseId) . '?page=' . $page);
    }

    /**
     * 新增add对应的保存
     */
    public function save() {
        // 存课程信息
        $Student = new Student();
        $courseId = Request::instance()->post('courseid/d');
        $page = Request::instance()->param('page'); 
        $grade = new  Grade();
        $Course = Course::get($courseId);
        if (is_null($Course)) {
            return $this->error('课程信息不存在', Request::instance()->header('referer'));
        }

        // 调用saveStudent方法用于保存新增数据，执行添加操作。
        if (!$this->saveStudent($Student)) {
            return $this->error('操作失败' . $Student->getError());
        }

        // 新建中间表对象，并保存中间表信息
        $CourseStudent = new CourseStudent();
        if (!$this->saveCourseStudent($Student->id, $courseId, $CourseStudent)) {
            return $this->error('中间表信息保存失败' . $Student->getError());
        }

        //新增成绩，并判断是否生成
        if(!$this->saveGrade($grade, $Student)) {
            $CourseStudent->delete();
            return $this->error('新增成绩失败' . $grade->getError());
        }

        // 课程对应学生人数也加一，并保存
        $Course->student_num ++;
        if (!$Course->validate(true)->save()) {
            $CourseStudent->delete();
            $Grade->delete();
            return $this->error('课程人数保存失败,请重新添加', Request::instance()->header('referer'));
        }

        //-----新增班级信息结束
        unset($Student);//在返回前最后被执行

        return $this->success('操作成功', url('index/Student/index?id=' . $courseId .'&page=' . $page));
    }

    /**
     * 学生信息保存
     * @param Student 将要被保存的学生对象
     * @param isUpdate 判断是否是数据更新
     */
	private function saveStudent(Student &$Student, $isUpdate = false, &$CourseStudent = null, &$Grade = null) {
        //写入要传入的数据
        $name = Request::instance()->post('name');
        $num = Request::instance()->post('num');
        $sex = Request::instance()->post('sex/d');
        $courseId = Request::instance()->post('courseid');
        $page = Request::instance()->param('page');
        
        // 根据姓名和学号判断是否已存在该学生信息
        $StudentTest = Student::get(['name' => $name, 'num' => $num]);
        $newStudent = new Student();

        // 判断是更新还是新增,如果是更新则更新中间表对象,新增在外边增加了中间表信息
        if ($isUpdate === false) {
            // 判断数据库是否存在该学生，存在则不用新增
            if (is_null($StudentTest)) {
                $Student->name = Request::instance()->post('name');
                $Student->num = Request::instance()->post('num');
                // 学生的用户名默认就是学号
                $Student->username = $Student->num;
                $Student->password = null;
                // 更新并保存数据
                return $Student->validate(true)->save();  
            }
        } else { 
            // 更新操作
            if (is_null($StudentTest)) {
                // 更新操作只更新学生对象信息
                $newStudent->name = Request::instance()->post('name');
                $newStudent->num = Request::instance()->post('num');
                // 学生的用户名默认就是学号
                $newStudent->username = $newStudent->num;
                // 更新并保存数据d
                trace($newStudent,'debug');
                if (!$newStudent->validate(true)->save()) {
                    return $this->error('学生信息保存失败,请联系管理员删除', Request::instance()->header('referer'));
                }
                // 第二种存在该学生的,直接将StudentTest赋给Student
            } else {
                $newStudent = $StudentTest;
            }
            // 修改中间表信息
            $CourseStudent->student_id = $newStudent->id;
            // 修改成绩信息
            $Grade->student_id = $newStudent->id;
            if (!$CourseStudent->validate(true)->save()) {
                return $this->error('中间表信息修改失败', Request::instance()->header('referer'));
            }
            if (!$Grade->validate(true)->save()) {
                return $this->error('成绩信息修改失败', Request::instance()->header('referer'));
            }
            return $this->error('更新成功', url('index/Student/index?id=' . $CourseStudent->course_id .'&page=' . $page));
        } 

        // 第三种情况：新增:数据库中存在该学生信息
        // 在这种情况应判断当前课程中是否已经存在该学生
        $que = array(
            'student_id' => $StudentTest->id,
            'course_id' => $courseId
        );
        $CourseStudentTest = CourseStudent::get($que);
        if (!is_null($CourseStudentTest)) {
            return $this->error('该学生已存在', Request::instance()->header('referer'));
        }
        $Student = $StudentTest;
        return 1;
    }

    /**
     * 中间表信息保存
     * @param studentId 中间表对应的student_id
     * @param courseId 中间表对应的字段course_id
     * @param CourseStudent 待保存中间表对象
     */
    public function saveCourseStudent($studentId, $courseId, CourseStudent &$CourseStudent) {
        $CourseStudent->student_id = $studentId;
        $CourseStudent->course_id = $courseId;

        // 将数据保存
        return $CourseStudent->save();
    }

    /**
     * 负责新增学生时新增成绩并保存
     * @param Grade 将要被保存的成绩
     * @param Student 该成绩对应的学生信息
     * @param isUpdate 是否是保存信息
     */
    private function saveGrade(Grade &$Grade,Student &$Student,$isUpdate= false) {
        // 实例化课程对象
        $courseId = Request::instance()->post('courseid');
        $Course = course::get($courseId); 

        // 写入要传入的数据
        $Grade->student_id = $Student->id;
        $Grade->course_id = $courseId;
        $Grade->resigternum = 0;
        $Grade->usgrade = 0;
        $Grade->coursegrade = $Course->begincougrade;
        if (!$Grade->save()) {
            return false;
        }

        // 保存后需要重新获取Grade
        $Grade = Grade::get(['student_id' => $Student->id, 'course_id' => $courseId]);

        // 获取该学生在该课程上课的记录
        $classCourses = ClassCourse::where('course_id', '=', $courseId)->select();
        foreach ($classCourses as $ClassCourse) {
            $que = array(
                'student_id' => $Student->id,
                'class_course_id' => $ClassCourse->id
            );
            $ClassDetail = ClassDetail::get($que);
            if (!is_null($ClassDetail)) {
                if ($ClassDetail->create_time < $ClassCourse->sign_deadline_time) {
                    $Grade->resigternum ++;
                }
                $Grade->coursegrade += $ClassDetail->aod_num;
                $Grade->getAllgrade();
            }
        }

        //更新并保存数据
        return true;
    }

    /**
     * 学生的删除功能
     */
    public function delete()
    {
        try {
            $Grade = new Grade();
            // 获取get数据
            $Request = Request::instance();
            $id = Request::instance()->param('id/d');
            $courseId = Request::instance()->param('course_id/d');
            // 实例化课程
            $Course = Course::get($courseId);
            // 调用checkPower方法判断权限
            $this->checkPower($courseId);

            //该课程学生总数减一
            $Course->student_num --;
            if (!$Course->save()) {
                return $this->error('学生人数更改失败', Request::instance()->header('referer'));
            }

            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception("未获取到ID信息", 1);
            }

            // 获取要删除的对象
            $Student = Student::get($id);
            $map = ['student_id'=>$id,
                    'course_id'=>$courseId
                    ];

            // 要删除的对象存在
            if (is_null($Student)) {
                throw new \Exception('不存在id为' . $id . '的学生，删除失败', 1);
            }

            if (false === $Student->CourseStudents()->where($map)->delete()) {
            return $this->error('删除学生课程关联信息发生错误' . $Student->CourseStudents()->getError());
            }
        
            if (false === $Grade->where($map)->delete()) {
            return $this->error('删除此学生该课程成绩关联信息发生错误' . $Grade->getError());
            }

            // 获取上课课程并删除对应的上课详情信息
            $classCourses = ClassCourse::where('course_id', '=', $courseId)->select();
            foreach ($classCourses as $ClassCourse) {
                if (!is_null($ClassCourse)) {
                    if (ClassDetail::where('class_course_id', '=', $ClassCourse->id)->delete() === false) {
                        return $this->error('上课详情信息删除失败', Request::instance()->header('referer'));
                    }
                }
            }
        }
            
        //  获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        catch(\think\Exception\HttpResponseException $e){
            throw $e;
            //获取到正常的异常时，输出异常
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
	// 进行跳转
	return $this->success('删除成功', $Request->header('referer'));   
    }

    /**
     * 负责学生端登陆后的显示
     */
    public function afterSign() {
        // 获取学生id，并将学生对象实例化
        $studentId = session('studentId');
        $courseId = request()->param('courseId');
        if (!is_null($courseId)) {
            $Course = Course::get($courseId);
        } else {
            $Course = '';
        }

        $Student = Student::get($studentId);
        if (is_null($Student) || is_null($studentId)) {
            return $this->error('学生信息不存在,请重新登陆', Request::instance()->header('referer'));
        }

        // 获取成绩信息
        $courseStudents = CourseStudent::where('student_id', '=', $studentId)->select();
        // 定义课程数组,并将中间表对应的课程存入该数组
        $courses = array();
        foreach ($courseStudents as $CourseStudent) {
            // 获取对应的课程
            if (!is_null($CourseStudent->course)) {
                $courses[] = $CourseStudent->course;
            }
        }

        // 将签到过的课程也放入
        $classDetails = ClassDetail::where('student_id', '=', $studentId)->select();
        foreach ($classDetails as $ClassDetail) {
            $flag = 0;
            foreach ($courses as $Course) {
                if ($Course->id === $ClassDetail->classCourse->course_id) {
                    $flag = 1;
                }
            }
            // 如果flag还为0,说明之前的数组中没有该课程
            if ($flag === 0 && !is_null($ClassDetail->classCourse->course)) {
                $courses[] = $ClassDetail->classCourse->course;
            }
        }

        // 通过中间表和学生id，获取该学生所上的课程
        $que = array(
            'student_id' => $studentId,
        );
        $classDetails = ClassDetail::order('update_time desc')->where($que)->paginate();


        // 将数据传入V层进行渲染
        $this->assign('classDetails', $classDetails);
        $this->assign('Student', $Student);
        $this->assign('Course', $Course);
        $this->assign('courses', $courses);
        return $this->fetch();
    }

    /**
     * 学生微信端密码修改
     */
    public function changePassword() {
        // 获取学生id
        $studentId = Request::instance()->param('studentId');
        if (is_null($Student = Student::get($studentId))) {
            return $this->error('学生信息实例化失败', Request::instance()->header('referer'));
        }

        // 获取新旧密码防止第一次设置失败
        $oldPassword = Request::instance()->param('oldPassword');
        $newPassword = Request::instance()->param('newPassword');
        $newPasswordAgain = Request::instance()->param('newPasswordAgain');

        $this->assign('Student', $Student);
        $this->assign('oldPassword', $oldPassword);
        $this->assign('newPassword', $newPassword);
        $this->assign('newPasswordAgain', $newPasswordAgain);

        return $this->fetch();
    }

    /**
     * 密码更新
     */
    public function passwordUpdate() {
        // 接收新旧密码和学生id，并实例化学生对象
        $studentId = Request::instance()->param('studentId');
        $oldPassword = Request::instance()->param('oldPassword');
        $newPassword = Request::instance()->param('newPassword');
        $newPasswordAgain = Request::instance()->param('newPasswordAgain');
        if (is_null($Student = Student::get($studentId))) {
            return $this->error('学生信息接收失败', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&studentId' . $Student->id . '&newPasswordAgain=' . $newPasswordAgain));
        }

        // 首先判断输入的原密码是否正确
        if ($Student->password !== $Student->encryptPassword($oldPassword)) {
            return $this->error('修改失败,原密码不正确', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&studentId' . $Student->id . '&newPasswordAgain=' . $newPasswordAgain));
        } else {
            // 原密码输入正确时
            if ($newPasswordAgain === $newPassword) {
                $Student->password = $Student->encryptPassword($newPassword);
                if (!$Student->save()) {
                    return $this->error('新密码保存失败,请重新修改', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&studentId' . $Student->id . '&newPasswordAgain=' . $newPasswordAgain));
                } else {
                    // 如果新密码长度不符合要求，返回重新修改
                    if (20 < strlen($newPassword) || strlen($newPassword) < 6) {
                        return $this->error('密码长度限制:6至20位', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&studentId' . $Student->id . '&newPasswordAgain=' . $newPasswordAgain));
                    } else {
                        if($newPasswordAgain === $oldPassword) {
                            return $this->error('密码长度限制:6至20位', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&newPasswordAgain=' . $newPasswordAgain));
                        }
                    }
                    session('studentId',null);
                    return $this->success('密码修改成功,请重新登陆', url('Login/studentwx?username=' . $Student->username));
                }
            } else {
                return $this->error('两次密码不相同，请确认新密码一致', url('changePassword?oldPassword=' . $oldPassword . '&newPassword=' . $newPassword . '&studentId' . $Student->id . '&newPasswordAgain=' . $newPasswordAgain));
            }
        }
    }

    /**
     * 学生注销方法
     */
    public function logOut() {
        // 直接将session清空
        session('studentId', null);

        // 跳转到登陆界面
        return $this->success('注销成功', url('Login/studentWx'));
    }

    /**
     * 权限判断
     * @param courseId 待判断的课程id
     */
    public function checkPower($courseId) {
        // 实例化课程对象，并判断是否为空
        if (is_null($Course = Course::get($courseId))) {
            return $this->error('课程信息不存在', Request::instance()->header('referer'));
        }

        // 获取教师id，并增加权限判断
        $teacherId = session('teacherId');
        if (is_null($teacherId)) {
            $url = url('index/login/index');
            header("Location: $url");
            exit();
        } 
        if ($teacherId !== $Course->teacher_id) {
            return $this->error('无此权限', Request::instance()->header('referer'));
        }
    }
}