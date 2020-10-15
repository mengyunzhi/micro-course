<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Student;
use app\common\model\CourseStudent;
use think\Request;
use app\common\model\Course;
class StudentController extends Controller
{
	 public function index()
    {
        try {
            // 获取查询信息
            $num = Request::instance()->get('num');

            $pageSize = 5; // 每页显示5条数据

            // 实例化Teacher
            $Student = new Student; 

            // 定制查询信息
            if (!empty($num)) {
                $Student->where('num', 'like', '%' . $num . '%');
            }


            // 按条件查询数据并调用分页
            $students = $Student->paginate($pageSize);

            // 向V层传数据
            $this->assign('students', $students);

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
		$courses = Course::all();
        $this->assign('courses', $courses);
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
		//新增数据并验证
		if (!Student::validate(true)->save()) {
			return $this->error('保存错误'.$Student->getError());
		}
		//---------新增课程-----
		//接收course_id这个数组
		$courseIds = Request::instance()->post('course_id/a');//a:获取类型为数组
		if(!is_null($courseIds)){
			if(!$CourseStudent->validate(true)->saveAll($datas)){
				return $this->error('课程-学生信息保存错误'.$CourseStudent->getError());
			}
		}
		unset($Student);
		return $this->success('操作成功'.url('index'));
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
		return $this->success('更新成功',url('index'));
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
