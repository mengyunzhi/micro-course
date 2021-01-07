<?php
namespace app\index\controller;
use app\common\model\Student;
use app\common\model\Course;
use think\Request;
use app\index\controller\Login;
use app\common\model\Grade;
use app\common\model\Seat;
use app\common\model\Classroom;
use app\common\model\CourseStudent;
class StudentController extends IndexController
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
            $course = Course::get($id);
            if($course->teacher->id != session('teacherId')){
                $this->error('无此权限');
            }
            // 每页显示5条数据
            $pageSize = 2; 

            // 定制查询信息
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$id);
                    $courseStudents = $courseStudents->
                    join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate($pageSize);
                } else {
                    $courseStudents = CourseStudent::where('course_id', '=', $id)->paginate($pageSize);
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
        $page = Request::instance()->param('page');
        $grade = new Grade();
        $grade->course_id = $courseId;

        //获取该ID对应的课程信息
        $course = Course::get($courseId);
		$Student=new Student;
		$Student->sex=0;
		$Student->email='';
		$Student->num='';
		$Student->name='';
		$Student->id=0;
         
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
		$id=Request::instance()->param('id/d');
        $page = Request::instance()->param('page');
        $course_id=Request::instance()->param('course_id/d');

        // 对课程和学生进行实例化
        $Course =Course::get($course_id);
        $courses = new Course();
		//判断是否存在为此id的记录
		if(is_null($Student = Student::get($id))) {
			return $this->error('未找到ID为'.id.'的记录');
		}

		//取出班级列表
		$this->assign('Student',$Student);
        $this->assign('page',$page);
        $this->assign('course',$courses);
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

        // 增加判断是否当前页数合规
        if($page === 0) {
            $page = 1;
        }

        // 学生信息更新并保存
        if (!$this->saveStudent($Student, true)) {
            return $this->error('操作失败' . $Student->getError());
        }
        $this->success('操作成功', url('Student/index?id=' . $courseId) . '?page=' . $page);
    }

    /**
     * 新增add对应的保存
     */
     public function save()
    {
        // 存课程信息
        $Student = new Student();
        $courseId = Request::instance()->post('courseid/d');
        $page = Request::instance()->param('page'); 
        $grade = new  Grade();
        $Course = Course::get($courseId);

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
            return $this->error('新增成绩失败' . $grade->getError());
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
	private function saveStudent(Student &$Student, $isUpdate = false) {
        //写入要传入的数据
        $name = Request::instance()->post('name');
        $num = Request::instance()->post('num/d');
        $sex = Request::instance()->post('sex/d');
        $email = Request::instance()->post('email');
        $courseId = Request::instance()->post('courseid');
 
        // 判断是更新还是新增,如果是新增则新增中间表对象
        if ($isUpdate === false) {
            // 根据姓名和学号判断是否已存在该学生信息
            $StudentTest = Student::get(['name' => $name, 'num' => $num]);
            if (is_null($StudentTest)) {
                $Student->name = Request::instance()->post('name');
                $Student->num = Request::instance()->post('num/d');
                $Student->sex = Request::instance()->post('sex/d');
                $Student->email = Request::instance()->post('email');
                //更新并保存数据
                return $Student->validate(true)->save();  
            }
        } else {
            // 更新操作只更新学生对象信息
            $Student->name = Request::instance()->post('name');
            $Student->num = Request::instance()->post('num/d');
            $Student->sex = Request::instance()->post('sex/d');
            $Student->email = Request::instance()->post('email');
            //更新并保存数据
            return $Student->validate(true)->save();  
        } 

        // 第三种情况:数据库中存在该学生信息
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
        // 写入要传入的数据
        $Grade->student_id = $Student->id;
        $Grade->course_id = Request::instance()->post('courseid');
        $Grade->resigternum = 0;
        $Grade->usgrade = 0;

        // 实例化课程对象
        $courseId = Request::instance()->post('courseid');
        $Course = course::get($courseId); 

        // 通过课程对上课表现初始成绩进行赋值
        $Grade->coursegrade = $Course->begincougrade;        
        $Grade->allgrade = $Grade->usgrade + $Grade->coursegrade;

        //更新并保存数据
        return $Grade->validate(true)->save();
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
            $course_id = Request::instance()->param('course_id/d');
            
            // 实例化课程
            $Course = Course::get($course_id);
            //该课程学生总数减一
            $Course->student_num--;

            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception("未获取到ID信息", 1);
            }

            // 获取要删除的对象
            $Student = Student::get($id);
            $map = ['student_id'=>$id,
                    'course_id'=>$course_id
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
}