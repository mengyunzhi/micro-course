<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;

/**
 * 
 */
class CourseController extends IndexController
{
     public function index()
    {
        try {
            // 获取查询信息
            $id = Request::instance()->param('id');
            
            //实例化课程
            $course = Course::get($id);
            $pageSize = 5; // 每页显示5条数据


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


    //添加操作
    public function add()
    {
        $Course=new Course;
        $Course->name='';
        $Course->id=0;
        $this->assign('Course',$Course);
        $Students = new Student;
        return $this->assign('students',$Students);
        return $this->fetch('edit');
    }


     public function save()
    {
        // 存课程信息
        $Course = new Course();
        $Course->name = Request::instance()->post('name');

        // 新增数据并验证。验证类，自己写下吧。
        if (!$Course->validate(true)->save()) {
            return $this->error('保存错误：' . $Course->getError());
        }
        //接收klass_id这个数组
        $studentIds = Request::instance()->post('student_id/a');       // /a表示获取的类型为数组

        // 利用klass_id这个数组，拼接为包括klass_id和course_id的二维数组。
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
        // 获取当前课程
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
