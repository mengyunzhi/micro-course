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
use app\common\model\ClassDetail;
use app\common\model\ClassCourse;
use app\common\model\Grade;
use app\common\model\Gradeaod;

/**
 * 管理员课程端
 */
class AdminCourseController extends AdminJudgeController {
    public function index()
    {
    	try{
            $Term = new Term;
           
    		$teacher_id=Request::instance()->param('id/d');
    		$name = Request::instance()->get('name');
    		$courses = new Course;
    		$pageSize=5;
            $que = [
                'state' => 1
            ];
            $Term = Term::get($que);
            if(is_null($Term)) {
            return $this->error('当前无学期开放', $_SERVER['HTTP_REFERER']);
        }
    	if(!empty($teacher_id))
    	{
    	    $pageSize = 5;
       	    $courses = Course::where('teacher_id', '=', $teacher_id);
       	    
       	    if (!empty($name)) {
        	$courses = $courses->where('name', 'like','%'.$name.'%');
            }
        }
       
        $courses = $courses->where('term_id','=', $Term->id);
        
        $courses = $courses->order('id desc')->paginate($pageSize);
        $page = $courses->render(); 

        $this->assign('termId', $Term->id);
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
	public function add() {
        $termId = input('param.termId');
    	$course = new Course();
        // 获取所有的教师信息
        $teachers = Teacher::all();
        $this->assign('termId', $termId);
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
            $this->deleteCourseRelevance($id);

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
     * 删除与课程相关的信息
     * @param $courseId 课程信息
     */
    public function deleteCourseRelevance($courseId) {
        $this->deleteCourseStudent($courseId);
        $this->deleteClassDetail($courseId);
        $this->deleteGrade($courseId);
        $this->deleteGradeaod($courseId);
    }

    /**
     * 删除课程学生关联
     * @param $courseId 要被删除的课程id
     */
    public function deleteCourseStudent($courseId) {
        $courseStudents = CourseStudent::where('course_id', '=', $courseId)->select();
        foreach ($courseStudents as $CourseStudent) {
            if(!$CourseStudent->delete()) {
                return $this->error('删除课程学生关联失败');
            }
        }
        return 1;
    }

    /**
     * 删除ClassDetail
     * @param $courseId 要被删除的课程id
     */
    public function deleteClassDetail($courseId) {
        $classcourses = Classcourse::where('course_id', '=', '$courseId')->select();
        foreach ($classcourses as $Classcourse ) {
            $classDetails = ClassDetail::where('class_course_id', '=', $Classcourse->id)->select();
            foreach ($classDetails as $ClassDetail) {
                if(!$ClassDetail->delete()) {
                    return $this->error('删除课程详细信息失败', url($url));
                }
            }
            if(!$Classcourse->delete()) {
                return $this->error('删除课程失败', url($url));
            }
        }
        return 1;
    }

    /**
     * 删除ClassDetail
     * @param $courseId 要被删除的课程id
     */
    public function deleteGrade($courseId) {
        $grades = Grade::where('course_id', '=', $courseId)->select();
        foreach ($grades as $Grade) {
            if(!$Grade->delete()) {
                return $this->error('删除对应课程成绩失败');
            }
        }
        return 1;
    }

    /**
     * 删除ClassDetail
     * @param $courseId 要被删除的课程id
     */
    public function deleteGradeaod($courseId) {
        $gradeaods = Gradeaod::where('course_id', '=', $courseId)->select();
        foreach ($gradeaods as $Gradeaod) {
            if(!$Gradeaod->delete()) {
                return $this->error('删除课程加减分情况失败');
            }
        }
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
        $Course->term_id = $Request->post('term_id/d');

        // 添加数据
        if (!$Course->validate(true)->save()) {
            return $this->error('数据添加错误：' . $Course->getError());
        }

        return $this->success('操作成功', url('index?id=' . $Course->teacher_id));
    }
}