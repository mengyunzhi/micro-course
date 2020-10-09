<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Grade;


/**
 * 
 */
class GradeLookController extends IndexController
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

            $Students = $course->Students;

            $Grades = Grade::where('course_id', 'like', '%' . $id . '%')->paginate($pageSize);



            if (!empty($id)) {

            }

            // 向V层传数据
            $this->assign('students', $Students);
            $this->assign('grades', $Grades);
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

    

    public function edit()
    {
        $id=Request::instance()->param('id/d');


        //判断是否存在为此id的记录

        if(is_null($Grade=Grade::get($id)))
        {
            return $this->error('未找到ID为'.id.'的记录');
        }

        //向V层传值
        $this->assign('Grade',$Grade);

        return $this->fetch();
    }


    public function update()
    {
        // 接收数据，取要更新的关键字信息
        $id = Request::instance()->post('id/d');

        // 获取当前对象
        $Grade = Grade::get($id);

        if (!is_null($Grade)) {
            if (!$this->saveGrade($Grade,true)) {
                return $this->error('操作失败' . $Grade->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }
    

    private function saveGrade(Grade &$Grade, $isUpdate = false) 
    {
        // 写入要更新的数据
        $Grade->usgrade = Request::instance()->post('usgrade');
        $Grade->coursegrade = Request::instance()->post('coursegrade');
        // 更新或保存
        return $Grade->validate(true)->save();
    }
}
