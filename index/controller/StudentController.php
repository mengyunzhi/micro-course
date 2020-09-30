<?php
namespace app\index\controller;
use app\common\model\Student;
use app\common\model\Course;
use think\Request;
use app\index\controller\Login;
class StudentController extends IndexController
{
	public function index()
	{
        $id = Request::instance()->param('id');
        echo $id;
        $course = Course::get($id);

		$students=$course->students;
		$this->assign('students',$students);
		return $this->fetch();
	}

	public function add()
	{
		$Student=new Student;
		$Student->sex=0;
		$Student->email='';
		$Student->num='';
		$Student->name='';
		$Student->id=0;


		$this->assign('Student',$Student);

		return $this->fetch('edit');
	}

	public function edit()
	{
		$id=Request::instance()->param('id/d');

        $courses =new Course();
		//判断是否存在为此id的记录

		if(is_null($Student=Student::get($id)))
		{
			return $this->error('未找到ID为'.id.'的记录');
		}

		//取出班级列表
		$this->assign('Student',$Student);
        $this->assign('course',$courses);
		return $this->fetch();
	}



	public function update()
	{
		//实例化请求
        $Request = Request::instance();
		$id=Request::instance()->post('id/d');
		$Student=Student::get($id);

		if(!is_null($Student))
        {
            if(!$this->saveStudent($Student,true))
            {
                return $this->error('操作失败'.$Student->getError());
            }  }else {
                return $this->error('当前操作的记录不存在');
                
            }
       return $this->success('更新成功', $Request->header('refrence')); 

	}

     public function save()
    {
        // 存课程信息
        $Student = new Student();
        $Student->name = Request::instance()->post('name');

        // 新增数据并验证。验证类
        if (!$Student->validate(true)->save()) {
            return $this->error('保存错误：' . $Student->getError());
        }
        //接收course_id这个数组
        $courseIds = Request::instance()->post('course_id/a');       // /a表示获取的类型为数组

        // 利用course_id这个数组，拼接为包括course_id和student_id的二维数组。
        if (!is_null($courseIds)) {
            if (!$Student->Courses()->saveAll($courseIds)) {
                return $this->error('课程-班级信息保存错误：' . $Student->Courses()->getError());
            }
        }
        //-----新增班级信息结束
        unset($Student);//在返回前最后被执行

        return $this->success('操作成功', url('index'));
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