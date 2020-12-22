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
class GradeController extends IndexController
{
     public function index()
    {
        try {
            // 获取查询信息
            $id =session('teacherId');
            
            //实例化课程
            $teacher =Teacher::get($id);
            // dump($teacher);
            // die();
            $pageSize = 5; // 每页显示5条数据

            // 定制查询信息
            if (!empty($id)) {

            }

            //获取该teacher对应的课程
            $Courses = Course::where('teacher_id', 'like', '%' . $id . '%')->paginate(2);

            $this->assign('teacher', $teacher);
            $this->assign('courses', $Courses);

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


    public function edit()
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
        // 接收数据，取要更新的关键字信息
        $id = Request::instance()->post('id/d');

        // 获取当前对象
        $Course = Course::get($id);

        if (!is_null($Course)) {
            if (!$this->saveGrade($Course,true)) {
                return $this->error('操作失败' . $Course->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }


    private function saveGrade(Course &$Course, $isUpdate = false) 
    {
        // 写入要更新的数据
        if (!$isUpdate) {
            
        }
        $Course->usmix = Request::instance()->post('usmix');
        $Course->courseup = Request::instance()->post('courseup');
        $Course->begincougrade = Request::instance()->post('begincougrade');
        $Course->resigternum = Request::instance()->post('resigternum');
        // 更新或保存
        return $Course->validate(true)->save();
    }
}
