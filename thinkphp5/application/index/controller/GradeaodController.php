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
            $id = Request::instance()->get('id');
            $course_id = Request::instance()->param('course_id');
            $grade_id = Request::instance()->param('grade_id');
            $aod_id = Request::instance()->param('aod_id');

            //实例化学生和成绩和课程
            $course = Course::get($course_id);
            $student =Student::get($id);
            $grade = Grade::get($grade_id);
            $pageSize = 5; // 每页显示5条数据


            $aod=array(
            "course_id"=>$course_id,
            "aodid"=>$aod_id
        );
            
            //获取该course对应的加分减分项
            $Gradeaods = Gradeaod::where($aod)->select();
            $this->assign('Student', $student);
            $this->assign('grade', $grade);
            $this->assign('gradeaods', $Gradeaods);
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

    public function update()
    {
        // 接收数据，取要更新的关键字信息
        $Request = Request::instance();
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
        return $this->success('更新成功', url('Coursegrade/index')); 
    }

    public function add()
    {
        $course_id = Request::instance()->post('course_id/d');
        $Course=Course::get($course_id);
        $grade_id = Request::instance()->post('grade_id/d');
        $Grade = Grade::get($grade_id);
        $id = Request::instance()->post('id');
        $Student= Student::get($id);

        //设置默认值
        $Gradeaod = new Gradeaod();

        $Gradeaod->name = '';
        $Gradeaod->aodnum = 0;
        $Gradeaod->aodid= 1;
        $Gradeaod->course_id= $course_id;

        $this->assign('gradeaod',$Gradeaod);
        $this->assign('grade',$Grade);
        $this->assign('Student',$Student);

        //调用edit模板
        return $this->fetch();
    }

    public function save()
    {
        // 接收数据，取要更新的关键字信息
        $Request = Request::instance();
        $id = Request::instance()->post('id/d');


        // 获取当前对象
        $Gradeaod = Gradeaod::get($id);

        if (!is_null($Gradeaod)) {
            if (!$this->saveGradeaod($Gradeaod,true)) {
                return $this->error('操作失败' . $Gradeaod->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('更新成功', url('index')); 
    }
    

    private function saveGrade(Grade &$Grade, $isUpdate = false) 
    {
        // 写入要更新的数据
        if (!$isUpdate) {
            
        }
        $Grade->coursegrade += Request::instance()->post('aodnum');
        
        // 更新或保存
        return $Grade->validate(true)->save();
    }

    private function saveGradeaod(Gradeaod &$Gradeaod, $isUpdate = false) 
    {
        // 写入要更新的数据
        if (!$isUpdate) {
            
        }
        $Gradeaod->aodname = Request::instance()->post('aodname');
        $Gradeaod->aodnum = Request::instance()->post('aodnum');
        
        // 更新或保存
        return $Gradeaod->validate(true)->save();
    }
}