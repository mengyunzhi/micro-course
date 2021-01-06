<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Student;
use app\common\model\CourseStudent;
use think\Request;
use app\common\model\Course;
use think\validate;
class AdminStudentController extends Controller
{
	 public function index()
    {
        try {
            // 获取查询信息
            $course_id = Request::instance()->param('id');
            $num = Request::instance()->get('num');
            $page = Request::instance()->get('page');
            $pageSize = 5; // 每页显示5条数据

            // 实例化Teacher
            $Student = new Student; 
            $coursestudent = new CourseStudent;
            //$course_id = 1;
            // 定制查询信息
            if(!empty($course_id)){
                // course-student表别名为a
            	$coursestudents = CourseStudent::alias('a')->where('a.course_id','=',$course_id);
                // student的表别名为s
                if(!empty($num)){
                    $coursestudents = $coursestudents->
                    join('student s','a.student_id = s.id')->where('s.num','=',$num);
                }

                // 按条件查询数据并调用分页
                $coursestudents = $coursestudents->paginate(5);
                $page = $coursestudents->render();
            }
           

            // 向V层传数据
            $this->assign('courseId', $course_id);
            $this->assign('coursestudents', $coursestudents);
            //dump($coursestudents);die();
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
	public function add(){
        $this->assign('courseId', input('param.courseId'));
		$this->assign('Student',new Student);
		return $this->fetch();
	}

	public function edit()
	{
		$id = Request::instance()->post('id/d');
		$Student = Student::get($id);

		if(is_null($Student)){
			return $this->error('不存在ID为'.$id.'的记录');
		}
		$this->assign('Student',$Student);
		return $this->fetch();
	}

	public function save()
	{
		$Student = new Student();
		$Student->name = Request::instance()->post('name');
        $Student->num = Request::instance()->post('num');
		//新增数据并验证
		if (!$Student->validate(true)->save()) {
			return $this->error('保存错误'.$Student->getError());
		}
		// -------------------------- 新增班级课程信息 -------------------------- 
        // 接course_id这个数组
        $courseIds = Request::instance()->post('course_id/a');       // /a表示获取的类型为数组

        // 利用klass_id这个数组，拼接为包括klass_id和course_id的二维数组。
        if (!is_null($courseIds)) {
            if (!$Student->courses()->saveAll($courseIds)) {
                return $this->error('课程-班级信息保存错误：' . $Student->courses()->getError());
            }
        }
        // -------------------------- 新增班级课程信息(end) -------------------------- 
		return $this->success('操作成功',$_POST['httpref']);
	}

	public function update()
	{
		//获取当前学生
		$id=Request::instance()->post('id/d');
		if(is_null($Student = Student::get($id))){
			return $this->error('不存在id为'.$id.'的记录');
		}
		//更新学生
		$Student->name = Request::instance()->post('name');
		if(is_null($Student->validate(true)->save())){
			return $this->error('学生信息更改发生错误'.$Student->getError());
		}
		$map = ['student_id'=>$id];
		//执行删除操作，由于可能存在删除0条记录的情况，用false来判断而不能使用
        // if (!KlassCourse::where($map)->delete()) {因为我们认为删除0条记录也为成功
		if (false===$Student->CourseStudent()->where($map)->delete()) {
			return $this->error('删除信息发生错误'.$Student->CourseStudents()->getError());
		}
		//增加新数据
		$courseIds = Request::instance()->post('course_id/a');
		if(!is_null($courseIds)){
			if (!$Student->Courses()->saveAll($courseIds)) {
				return $this->error('信息保存错误'.$Student->Courses()->getError());
			}
		}
		return $this->success('更新成功',$_POST['httpref']);
	}

	private function saveStudent(Student &$Student,$isUpdate= false)
    {
        //写入要传入的数据
        $Student->name=Request::instance()->post('name');

        if(!$isUpdate)
        {
            
            $Student->num=Request::instance()->post('num/d');
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
