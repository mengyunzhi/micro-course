<?php
namespace app\index\controller;
use app\common\model\Student;
use app\common\model\Course;
use think\Request;
use app\index\controller\Login;
use app\common\model\Grade;
use app\common\model\CourseStudent;
class StudentController extends IndexController
{
        public function index()
    {
        try {
            // 获取查询信息
            $id = Request::instance()->param('id');
            $num = Request::instance()->param('name');
            $page = Request::instance()->param('page');
            
            //实例化课程
            $course = Course::get($id);

            if($course->teacher->id != session('teacherId')){
                $this->error('无此权限');
            }
            $pageSize = 2; // 每页显示5条数据

            // 定制查询信息
            
            if(!empty($num)){
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$id);
                    $courseStudents = $courseStudents->
                    join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate($pageSize);
                }
            else {
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
    * 对应上课管理时的单击座位查看学生信息
    */
    public function look()
    {
        // 获取学生对应的id和课程对应id,实例化请求
        $Request = Request::instance();
        $id = Request::instance()->param('id');
        $courseId = Request::instance()->param('course_id');
        
        // 实例化对象，通过课程和学生对应的id得出上课时此学生此课程的成绩，方便进行加减分操作
        $Student = Student::get($id);
        $Course = Course::get($courseId);
        $grade=array(
            "course_id"=>$courseId,
            "student_id"=>$id
        );

        // select方法获取的是对象数组，下面传值时要传入首地址,增加判断是否有该学生的成绩信息
        $Grade = Grade::where($grade)->select();
        $number = sizeof($Grade);
        if ($number == 0) {
            return $this->error('未查到该学生对应该课程的成绩', $Request->header('referer'));
        }

        $this->assign('Student', $Student);
        $this->assign('Course', $Course);
        $this->assign('Grade', $Grade[0]);
        return $this->fetch();
    }


	public function add()
	{
        //获取正确课程对应的ID
        $id = Request::instance()->param('id');
        $page = Request::instance()->param('page');
        $grade = new Grade();
        $grade->course_id = $id;

        //获取该ID对应的课程信息
        $course = Course::get($id);
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

	public function edit()
	{
		$id=Request::instance()->param('id/d');
        $page = Request::instance()->param('page');
        $course_id=Request::instance()->param('course_id/d');

        $Course =Course::get($course_id);
        $courses = new Course();
		//判断是否存在为此id的记录

		if(is_null($Student=Student::get($id)))
		{
			return $this->error('未找到ID为'.id.'的记录');
		}

		//取出班级列表
		$this->assign('Student',$Student);
        $this->assign('page',$page);
        $this->assign('course',$courses);
        $this->assign('Course',$Course);
		return $this->fetch();
	}



	public function update()
    {
        // 获取当前学生
        $id = Request::instance()->post('id/d');
        $page=Request::instance()->post('page/d');
        $courseId = Request::instance()->post('courseid/d');
        if (is_null($Student = Student::get($id))) {
            return $this->error('不存在ID为' . $id . '的记录');
        }

        if($page==0)
        {
            $page = 1;
        }

        // 更新学生名
        $Student->name = Request::instance()->post('name');
        if (is_null($Student->validate(true)->save())) {
            return $this->error('学生信息更新发生错误：' . $Student->getError());
        }


        // 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断
        // 我们认为，删除0条记录，也是成功

        // 增加新增数据，执行添加操作。
        if (!$this->saveStudent($Student)) {
            return $this->error('操作失败' . $Student->getError());
        }

        $this->success('操作成功', url('Student/index?id=' . $courseId) . '?page=' . $page);
    }

     public function save()
    {
        // 存课程信息
        $Student = new Student();
        $courseId = Request::instance()->post('courseid/d');
        $page = Request::instance()->param('page');
        $Student->name = Request::instance()->post('name');
        $grade = new  Grade();
        $Course = Course::get($courseId);
        $Course->student_num++;

        // 新增数据并验证。
        if (!$Student->validate(true)->save()) {
            return $this->error('保存错误：' . $Student->getError());
        }
        //接收klass_id这个数组
        // /a表示获取的类型为数组

        // 增加新增数据，执行添加操作。
        if (!$this->saveStudent($Student)) {
            return $this->error('操作失败' . $Student->getError());
        }


        if (!is_null($courseId)) {
            if (!$Student->Courses()->save($courseId)) {
                return $this->error('课程-班级信息保存错误：' . $Student->Courses()->getError());
            }
        }

        //新增成绩，并判断是否生成
        if(!$this->saveGrade($grade,$Student))
        {
            return $this->error('新增成绩失败' . $grade->getError());
        }
        //-----新增班级信息结束
        unset($Student);//在返回前最后被执行

        return $this->success('操作成功', url('index/Student/index?id=' . $courseId .'&page=' . $page));
    }


	private function saveStudent(Student &$Student,$isUpdate= false)
    {
        //写入要传入的数据
        $Student->name=Request::instance()->post('name');

        if(!$isUpdate)
        {
            $Student->num=Request::instance()->post('num/d');
            $Student->sex=Request::instance()->post('sex/d');
            $Student->email=Request::instance()->post('email');
        }

        //更新并保存数据
        return $Student->validate(true)->save();
    }
    private function saveGrade(Grade &$Grade,Student &$Student,$isUpdate= false)
    {
        //写入要传入的数据
        $Grade->student_id=$Student->id;

            $Grade->coursegrade=Request::instance()->post('begincougrade/d');
            $Grade->usgrade=0;
            $Grade->course_id=Request::instance()->post('courseid');
            $Grade->resigternum=0;
            $Grade->allgrade=$Grade->usgrade+$Grade->coursegrade;

        //更新并保存数据
        return $Grade->validate(true)->save();
    }



    public function delete()
    {
        try {
            $Grade = new Grade;
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