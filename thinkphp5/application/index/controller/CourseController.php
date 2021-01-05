<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Term;
/**
 * 
 */
class CourseController extends IndexController
{
     
    public function index()
    {
        //接受传来的ID值
         $id =session('teacherId');
         $name = Request::instance()->param('name');

        //通过接受的id来实例化Teacher
         $Teacher=Teacher::get($id);
        //查询的情况时

        // 调用父类构造函数(必须)
        parent::__construct();
        //验证用户是否登录
        if(!Teacher::isLogin())
        {
            return $this->error('plz login first',url('Login/index'));
        }

        $course = Course::get($id);

        $pageSize=5;//每页显示5条数据

        //实例化Course
        $Course = new Course;
        
        //打印$Teacher 至控制台
        trace($Teacher,'debug');

        //按条件查询数据并调用分页
         $courses = $Course->where('teacher_id', 'like', '%' . $id . '%')->paginate($pageSize, false, [
            'query'=>[
                'id' => $id,
                ],
            ]);

         if (!empty($name))
         {
            $courses = Course::where('name', 'like', '%' . $name . '%')->paginate();
         }
 

        //向V层传数据
        $this->assign('courses',$courses);
        $this->assign('Teacher',$Teacher);
        //取回打包后的数据
        $htmls=$this->fetch();
        //将数据返回给用户
        return $htmls;
        
    }



    //添加操作
        public function add()
    {
        $id = Request::instance()->param('id');
        $Term = Term::where('state', 'like', '%' . 1 . '%')->paginate();      
        $Teacher = Teacher::get($id);
        $Course=new Course;
        $Course->name = '';

        //设置默认值
        // $Course->id=0;
        // $Course->name='';
        // dump($Term);
        // dump($Teacher);
        // dump($Course);
        // die();
        $this->assign('Course',$Course);
        $this->assign('Term',$Term[0]);
        $this->assign('Teacher',$Teacher);

        //调用edit模板
        return $this->fetch('edit');
    }


     public function save()
    {
        // 存课程信息
        $Course = new Course();
        $Course->name = Request::instance()->post('name');
        $Course->teacher_id = Request::instance()->post('id');
        $Course->term_id = Request::instance()->post('term_id');
        $Course->student_num = 0;

        // 新增数据并验证。验证类
        if (!$Course->validate(true)->save()) {
            return $this->error('保存错误：' . $Course->getError());
        }
        //接受学生导入信息
        $studentIds = Request::instance()->post('student_id/a');       // /a表示获取的类型为数组

        
        if (!is_null($studentIds)) {
            if (!$Course->Students()->saveAll($studentIds)) {
                return $this->error('课程-班级信息保存错误：' . $Course->Students()->getError());
            }
        }
        //-----新增班级信息结束
        unset($Course);//在返回前最后被执行

        return $this->success('操作成功', url('index'));
    }


    //编辑课程信息
    public function edit()
    {
        $id=Request::instance()->param('id/d');
        $Course=Course::get($id);

        //获取该课程对应的所有学生信息
        $Students = $Course->Students;

        if(is_null($Course)){
            return $this->error('不存在Id为:'.$id.'的课程');
        }

        $this->assign('Course',$Course);
        $this->assign('students',$Students);
        return $this->fetch();
    }

    //编辑课程名称，导入学生信息等功能
    public function courseedit()
    {
        $id=Request::instance()->param('id/d');
        $Course=Course::get($id);

        //获取该课程对应的所有学生信息

        if(is_null($Course)){
            return $this->error('不存在Id为:'.$id.'的课程');
        }


        $this->assign('Course',$Course);
        return $this->fetch();
    }

    public function update()
    {
        // 获取当前老师和课程ID
        $teacher_id = Request::instance()->post('teacher_id/d');
        $id = Request::instance()->post('id/d');
        if (is_null($Course = Course::get($id))) {
            return $this->error('不存在ID为' . $id . '的记录');
        }

        // 更新课程名
        $Course->name = Request::instance()->post('name');
        if (is_null($Course->validate(true)->save())) {
            return $this->error('课程信息更新发生错误：' . $Course->getError());
        }

        // 删除原有信息
        $map = ['course_id'=>$id];

        // 执行删除操作。由于可能存在 成功删除0条记录，故使用false来进行判断，而不能使用
        // if (!KlassCourse::where($map)->delete()) {
        // 我们认为，删除0条记录，也是成功
        if (false === $Course->CourseStudents()->where($map)->delete()) {
            return $this->error('删除班级课程关联信息发生错误' . $Course->CourseStudents()->getError());
        }

        // 增加新增数据，执行添加操作。
        $studentIds = Request::instance()->post('student_id/a');
        if (!is_null($studentIds)) {
            if (!$Course->Students()->saveAll($studentIds)) {
                return $this->error('课程-班级信息保存错误：' . $Course->Students()->getError());
            }
        }

        return $this->success('更新成功', url('index'));
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
}
