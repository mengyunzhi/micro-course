<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Gradeaod;
use app\common\model\Grade;


/**
 * 
 */
class GradeaodController extends IndexController
{
     public function index()
    {
        try {
            // 获取查询信息
            $id = Request::instance()->param('id');
            $course_id = Request::instance()->param('course_id');
            $grade_id = Request::instance()->param('grade_id');
            
            //实例化学生和成绩
            $student =Student::get($id);
            $grade = Grade::get($grade_id);
            $pageSize = 5; // 每页显示5条数据

            // 定制查询信息
            if (!empty($id)) {

            }



            //获取该course对应的加分减分项
            $Gradeaods = Gradeaod::where('course_id', 'like', '%' . $course_id . '%')->select();

            $this->assign('Student', $student);
            $this->assign('grade', $grade);
            $this->assign('gradeaods', $Gradeaods);

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
        if (!$isUpdate) {
            
        }
        $Grade->coursegrade += Request::instance()->post('gradeaod->aodnum');
        
        // 更新或保存
        return $Grade->validate(true)->save();
    }
}