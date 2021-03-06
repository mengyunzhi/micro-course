<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use think\Controller;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Gradeaod;
use app\common\model\Grade;
use app\common\model\Classroom;
use app\common\model\ClassDetail;
use app\common\model\ClassCourse;

/**
 * 负责平时成绩的加减和保存
 */
class GradeaodController extends IndexController
{
    /**
     * 对学生上课表现成绩的编辑界面，负责统计上课加减分项等，后传入update进行保留
     */
    public function index() {
        try {
            // 接收教室id:classroomId，学生id:studentId，加减分选择aodId
            $studentId = Request::instance()->param('studentId');
            $aodId = Request::instance()->param('aodId');
            $classroomId = Request::instance()->param('classroomId/d');

            // 实例化学生、教室对象
            $Student = Student::get($studentId);
            $Classroom = Classroom::get($classroomId);
            // 实例化课程对象
            if (!is_null($Classroom)) {
                if (is_null($Course = Course::get($Classroom->course_id)) || is_null($Classroom->course_id)) {
                    return $this->error('课程信息不存在', Request::instance()->header('referer'));
                }
            } else {
                return $this->error('教室信息不存在', Request::instance()->header('referer'));
            }

            // 根据教室信息获取当前对应的上课课程对象
            $ClassCourse = $this->getClassCourse($Classroom, $studentId);

            // 定制查询信息,通过教室对应的课程id和学生的studentId构造查询信息
            $que = array(
                "student_id" => $studentId,
                "course_id" => $Classroom->course_id
            );

            // 根据查询条件获取成绩对象：两种情况
            $Grade = Grade::get($que);
            // 第一种：成绩对象为空，说明老师还没导入学生信息
            if (is_null($Grade)) {
                // 为空时则要获取该学生对应的此节课的上课详情，通过上课详情进行加减分
                $que = array(
                    'student_id' => $studentId,
                    'class_course_id' => $ClassCourse->id
                );
                $ClassDetail = ClassDetail::get($que);
                if (is_null($ClassDetail)) {
                    return $this->error('信息不存在', Request::instance()->header('referer'));
                }
                $this->assign('ClassDetail', $ClassDetail);
            }
 
            // 定制查询信息，根据aodId和课程id获取该course对应的加分减分项
            $que = array(
                "course_id" => $Classroom->course_id,
                "aod_state" => $aodId
            );
            $Gradeaods = Gradeaod::where($que)->select();

            // 将实例化的值传入V层
            $this->assign('Student', $Student);
            $this->assign('Grade', $Grade);
            $this->assign('Course', $Course);
            $this->assign('Gradeaods', $Gradeaods);
            $this->assign('Classroom', $Classroom);

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

    /**
     * 获取上课课程对象
     * @param Classroom 查询所需的教室对象
     * @param studentId 学生对象id
     */
    public function getClassCourse($Classroom, $studentId)
    {
        // 构造查询条件
        $que = array(
            'begin_time' => $Classroom->begin_time
        );

        // 根据查询条件获取上课课程对象并返回
        $ClassCourse = ClassCourse::get($que);
        return $ClassCourse;
    }

    /**
     * 用于接收index传入的更新后的数据，并对数据进行保存和跳转
     */
    public function update() {
        // 接收教室Id和成绩id
        $Request = Request::instance();
        $gradeId = Request::instance()->post('gradeId/d');
        $classroomId = Request::instance()->post('classroomId/d');
        $classDetailId = Request::instance()->post('classDetailId/d');
        // 获取当前对象
        $Grade = Grade::get($gradeId);
        $Classroom = Classroom::get($classroomId);

        // 判断成绩是否获取成功，并保存修改后的成绩
        if (!is_null($Grade) || !is_null($gradeId)) {
            $this->saveGrade($Grade,true);
        } else {
            // 获取上课详情对象
            if (!is_null($classDetailId)) {
                $ClassDetail = ClassDetail::get($classDetailId);
                $ClassDetail->aod_num += Request::instance()->post('aodNum');
                $ClassDetail->save();
            } else {
                return $this->error('当前操作的记录不存在');
            }
        }
    
        // 成功跳转至InClass/index触发器,并传入教室id
        return $this->success('上课表现成绩保存成功', url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1)); 
    }

    /**
     * 增加新的加减分项，并将输入的值传到save进行保存
     */
    public function add() {
        // 获取从index传入的值，有学生id，课程id，教室id
        $courseId = Request::instance()->param('courseId/d');
        $classroomId = Request::instance()->param('classroomId/d');
        $studentId = Request::instance()->param('studentId');
        $classDetailId = Request::instance()->param('classDetailId');
        $va = Request::instance()->param('va/d');

        // 判断是否是未导入学生情况进行加减分
        $ClassDetail = '';
        if (!is_null($classDetailId)) {
            // 获取上课详情对象
            $ClassDetail = ClassDetail::get($classDetailId);
        }

        // 实例化教室对象、课程对象、学生对象
        $Classroom = Classroom::get($classroomId);
        $Course = Course::get($courseId);
        $Student = Student::get($studentId);

        // 新增增减项，并设置默认值
        $Gradeaod = new Gradeaod();
        $Gradeaod->name = '';
        $Gradeaod->aod_num = 0;
        $Gradeaod->aod_name = '';
        $Gradeaod->aod_state= 1;
        $Gradeaod->course_id= $courseId;
        $Gradeaod->id=0;

        // 将数据传入V层
        $this->assign('gradeaod',$Gradeaod);
        $this->assign('Course', $Course);
        $this->assign('Student', $Student);
        $this->assign('va', $va);
        $this->assign('Classroom', $Classroom);
        $this->assign('ClassDetail', $ClassDetail);

        return $this->fetch('edit');
    }

    /**
     * 负责保存新增加的加减分项，同时完成对学生的加减分
     */
    public function save() {
        // 获取请求传入的学生id和教室id
        $studentId = Request::instance()->param('studentId');
        $classroomId = Request::instance()->param('classroomId');
        $classDetailId = Request::instance()->param('classDetailId');

        // 实例化教室对象和学生对象
        $Student = Student::get($studentId);
        $Classroom = Classroom::get($classroomId);

        // 构造条件数组，获取该学生的成绩
        $que = array(
            "student_id" => $studentId,
            "course_id" => $Classroom->Course->id
        );
        $Grade = Grade::get($que);

        // 新增加减分项并保存
        $Gradeaod = new  Gradeaod();
        if (!$this->saveGradeaod($Gradeaod, true)) {
            return $this->error('操作失败' . $Gradeaod->getError());
        }

        // 通过新增加的加减分项对$Grade对象进行加减分
        if (!is_null($Grade)) {
            $this->saveGrade($Grade);
        } else {
            // 考虑老师未导入学生情况
            if (!is_null($classDetailId)) {
                $ClassDetail = ClassDetail::get($classDetailId);
                $ClassDetail->aod_num += Request::instance()->post('aodNum');
                if ($ClassDetail->save()) {
                    return $this->success(
                        '成绩修改成功，请尽快导入学生信息',
                        url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1)
                    );
                }
            } else {
                return $this->error('当前操作的记录不存在');
            }
        }

        // 成功跳转至index触发器
        return $this->success('新增成功,学生成绩修改成功', url('InClass/index?classroomId=' . $Classroom->id . '&reclass=' . 1)); 
    }
    
    /**
     * 保存成绩，用于保存上课对上课表现成绩加减分后成绩的保存
     * @param Grade 将要保存的成绩
     */
    private function saveGrade(Grade &$Grade) {
        // 接收加减分传入的加减分分值，并将上课表现成绩进行修改
        $Grade->coursegrade += Request::instance()->post('aodNum');
        
        // 重新计算总成绩并保存
        $Grade->getAllgrade();
    }

    /**
     * 新增的加减分项属性内容的保存
     * @param Gradeaod 新增的加减分对象
     */
    private function saveGradeaod(Gradeaod &$Gradeaod) {
        // 接收从add传入的加减分项的名称和分值
        $Gradeaod->aod_name = Request::instance()->post('aodName');
        $Gradeaod->aod_num = Request::instance()->post('aodNum');
        // 增加判断，如果分值小于0，那么aod_state等于0,否则等于1
        if($Gradeaod->aod_num<0) {
            $Gradeaod->aod_state=0;
        } else {
            $Gradeaod->aod_state=1;
        }
        $Gradeaod->course_id = Request::instance()->post('courseId');
        
        // 更新或保存
        return $Gradeaod->validate(true)->save();
    }
}