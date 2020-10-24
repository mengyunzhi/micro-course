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
            
            //实例化课程
            $course = Course::get(2);
            $pageSize = 5; // 每页显示5条数据

            // 实例化students和CourseStudent    
            // $students = new Student;
            // $courseStudents =new CourseStudent;

            // 定制查询信息
            if (!empty($id)) {
            }

            $Students = $course->Students;
           // $Students = new Teacher();
            // $arraytest = [new Student(), new Student()]; 
            //dump($Students);
            // 按条件查询数据并调用分页
            //die();

            // 向V层传数据
            $this->assign('students', $Students);
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


	public function add()
	{
        //获取正确课程对应的ID
        $id = Request::instance()->param('id');
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
         
        
        $this->assign('Course',$course);
        $this->assign('grade',$grade);
		$this->assign('Student',$Student);

		return $this->fetch('edit');
	}

	public function edit()
	{
		$id=Request::instance()->param('id/d');

        $page=Request::instance()->param('page/d');
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
        $page=Request::instance()->param('page/d');
        $courseId = Request::instance()->post('course_id/d');
        $courseId = Request::instance()->post('course_id/d');
        if (is_null($Student = Student::get($id))) {
            return $this->error('不存在ID为' . $id . '的记录');
        }

        // 更新学生名
        $Student->name = Request::instance()->post('name');
        if (is_null($Student->validate(true)->save())) {
            return $this->error('学生信息更新发生错误：' . $Student->getError());
        }

        // 删除原有信息
        $map = ['student_id'=>$id];

        // 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断
        // 我们认为，删除0条记录，也是成功
        if (false === $Student->CourseStudents()->where($map)->delete()) {
            return $this->error('删除学生课程关联信息发生错误' . $Student->CourseStudents()->getError());
        }

        // 增加新增数据，执行添加操作。
        if (!$this->saveStudent($Student)) {
            return $this->error('操作失败' . $Student->getError());
        }
        $courseIds = Request::instance()->post('course_id/a');
        if (!is_null($courseIds)) {
            if (!$Student->Courses()->saveAll($courseIds)) {
                return $this->error('学生-课程信息保存错误：' . $Student->Courses()->getError());
            }
        }

        $this->success('操作成功', url('course/index?id=' . $courseId) . '?page=$page');
    }

     public function save()
    {
        // 存课程信息
        $Student = new Student();
        $courseid = Request::instance()->post('courseid/d');
        $Student->name = Request::instance()->post('name');
        $grade = new  Grade();


        // 新增数据并验证。验证类，自己写下吧。
        if (!$Student->validate(true)->save()) {
            return $this->error('保存错误：' . $Student->getError());
        }
        //接收klass_id这个数组
        $courseIds = Request::instance()->post('course_id/a');       // /a表示获取的类型为数组

        // 增加新增数据，执行添加操作。
        if (!$this->saveStudent($Student)) {
            return $this->error('操作失败' . $Student->getError());
        }
        // 利用klass_id这个数组，拼接为包括klass_id和course_id的二维数组。
        if (!is_null($courseIds)) {
            if (!$Student->Courses()->saveAll($courseIds)) {
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

        return $this->success('操作成功', url('index/Course/index?id=' . $courseid .'&page=3'));
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
            $Grade->registernum=0;
            $Grade->allgrade=$Grade->usgrade+$Grade->coursegrade;

        //更新并保存数据
        return $Grade->validate(true)->save();
    }



    public function delete()
    {
        try {
            // 获取get数据
            $Request = Request::instance();
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception("未获取到ID信息", 1);
            }

            // 获取要删除的对象
            $Student = Student::get($id);

            // 要删除的对象存在
            if (is_null($Student)) {
                throw new \Exception('不存在id为' . $id . '的学生，删除失败', 1);
            }

            // 删除对象
            if (!$Student->delete()) {
                $message = '删除失败:' . $Teacher->getError();
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