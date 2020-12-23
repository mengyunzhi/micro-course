<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;//教师模型
use app\common\model\Course;//教师模型
use app\common\model\Student;//教师模型
use app\common\model\CourseStudent;
use think\Request;
use think\validate;
use app\common\model\Term;


class AdminCourseController extends Controller
{
  public function index()
    {
    	try{
            $Term = new Term;
    		$teacher_id=Request::instance()->param('id/d');
    		$name = Request::instance()->get('name');
    		$courses = new Course;
    		$pageSize=5;
            $Term = $Term->where('state','=',1);
    	if(!empty($teacher_id))
    	{
    	    $pageSize = 5;
       	    $courses = Course::where('teacher_id', '=', $teacher_id);
       	  
       	    if (!empty($name)) {

        	$courses = $courses->where('name', 'like','%'.$name.'%');
            }

        }
        $courses = $courses->where('term_id','=',Term::$Term_id);
        $courses = $courses->order('id desc')->paginate($pageSize, false, [
                'query'=>[
                    'name' => $name,
                    ],
                ]);
        $page = $courses->render(); 


            $this->assign('courses',$courses);
            $this->assign('page',$page);
       	    return $this->fetch();
        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }
    public function insert()
    {
        // 实例化Term空对象
        $Course = new Course();
        
        // 为对象的属性赋值
        $Course->id=1;
        $Course->name=$postData['name'];
        $Course->teacher_id=$id;
     
        // 执行对象的插入数据操作
        $Course->save();
        return $Course->name . '成功增加至数据表中。新增ID为:' . $Course->id;
    }
	public function add()
    {
    	$course = new Course();
        // 获取所有的教师信息
        $teachers = Teacher::all();
        $this->assign('teachers', $teachers);
        $this->assign('course', $course);
        return $this->fetch();
    }
    public function edit()
    {
        $id = Request::instance()->param('id/d');
        //获取所有教师信息
        $teachers = Teacher::all();
        $this->assign('teachers',$teachers);
        //获取用户操作的课程信息
        if(false==$course=Course::get($id)){
        	return $this->error('未找到ID为'.$id.'的记录');
        }
        $this->assign('course',$course);
        return $this->fetch();
    }

    public function update(){
    	$id = Request::instance()->post('id/d');
    	//获取传入的课程信息
    	$course = Course::get($id);
    	if(is_null($course)){
    		return $this->error('系统未找到ID为'.$id.'的记录');}
    		//数据更新
    		$course->name = Request::instance()->post('name');
    		$course->teacher_id = Request::instance()->post('teacher_id');
    		if(!$course->validate()->save()){
    			return $this->error('更新错误：'.$course->getError());
    		}
    		else{
    				return $this->success('操作成功',$_POST['httpref']);
    		}
    	
    }

    public function delete()
    {
        try {
            // 实例化请求类
            $Request = Request::instance();
            
            // 获取get数据
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }

            // 获取要删除的对象
            $Course = Course::get($id);

            // 要删除的对象存在
            if (is_null($Course)) {
                throw new \Exception('不存在id为' . $id . '的课程，删除失败', 1);
            }

            // 删除对象
            if (!$Course->delete()) {
                return $this->error('删除失败:' . $Course->getError());
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

        // 进行跳转 
        return $this->success('删除成功', $Request->header('referer')); 
    }
    /**
     * 对数据进行保存或更新
     * @param    Course                  &$Course 教师
     * @return   bool                             
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-10-24T15:24:29+0800
     */
    private function saveCourse(Course &$AdminCourse) 
    {
        // 写入要更新的数据
        $Course->name = input('post.name');

        // 更新或保存
        return $Course->validate(true)->save();
    }
     public function save()
    {
        // 实例化请求信息
        $Request = Request::instance();

        // 实例化班级并赋值
        $Course = new Course();
        $Course->name = $Request->post('name');
        $Course->teacher_id = $Request->post('teacher_id/d');

        // 添加数据
        if (!$Course->validate(true)->save()) {
            return $this->error('数据添加错误：' . $Course->getError());
        }

        return $this->success('操作成功', url('course/index?id=' . $Course->teacher_id));
    }
}