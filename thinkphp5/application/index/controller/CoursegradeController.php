<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Grade;
use app\common\model\Classroom;

/**
 * 用于在上课时对于上课表现成绩的查看
 */
class CoursegradeController extends IndexController {
    public function index() {
        try {
            // 接收教室id，接收学生id
            $classroomId = Request::instance()->param('classroomId');
            $studentId = Request::instance()->param('studentId');
            
            // 实例化教室和学生
            $Classroom = Classroom::get($classroomId);
            $Student = Student::get($studentId);

            // 增加判断点击按钮是否存在学生
            if (is_null($studentId)) {
                return $this->error('该座位不存在学生', url('InClass/index?classroomId=' . $Classroom->id . '$reclass=' . 1));
            }

            // 定制成绩查询数组，以求该学生该课程成绩
            $que = array(
                "student_id" => $studentId,
                "course_id" => $Classroom->course_id
            );

            // 通过条件查询，获得该学生该课程对应的上课成绩
            $Grade = Grade::get($que);
            
            // 向V层传数据
            $this->assign('Student', $Student);
            $this->assign('Grade', $Grade);
            $this->assign('Classroom', $Classroom);
            $this->assign('Course', $Classroom->Course);

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
}