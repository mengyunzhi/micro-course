<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Student;
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
		$Student=new Student;
		$Student->num='';
		$Student->name='';
		$Student->gscore='';
		$Student->lscore='';
		$Student->id=0;

		$this->assign('Student',$Student);

		return $this->fetch('edit');
	}

	public function edit()
	{
		$id=Request::instance()->param('id/d');

		//判断是否存在为此id的记录

		if(is_null($Student=Student::get($id)))
		{
			return $this->error('未找到ID为'.id.'的记录');
		}

		//取出班级列表
		$this->assign('Student',$Student);
		return $this->fetch();
	}

	public function save()
	{
		//实例化请求信息
    	$Student=new Student;
		//新增数据
		if (!$this->saveStudent($Student)) {
            return $this->error('操作失败' . $Student->getError());
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
	}

	public function update()
	{
		//实例化请求
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
       return $this->success('操作成功',url('index'));

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
