<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Grade;
use app\common\model\Teacher;


/**
 * 成绩管理，负责总成绩的展示和编辑部分
 */
class GradeController extends IndexController {
     public function index() {
        try {
            // 获取查询信息
            $id =session('teacherId');
            
            //实例化课程
            $teacher =Teacher::get($id);

            $pageSize = 5; // 每页显示5条数据

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

    /**
     * 负责记录修改签到成绩占比和上课表现成绩占比和上课表现成绩初始值，以及上课表现上限
     */
    public function edit() {
        // 接收课程id，并实例化课程对象
        $courseId = Request::instance()->param('id/d');
        $Course = Course::get($courseId);
        if ($teacherId = session('teacherId') !== $Course->teacher_id) {
                return $this->error('无此操作', Request::instance()->header('referer'));
            }

        // 增加判断是否课程存在
        if(is_null($Course)) {
            return $this->error('不存在Id为:' . $courseId . '的课程');
        }

        $this->assign('Course',$Course);
        return $this->fetch();
    }
    
    /**
     * 对从edit传入的数据进行赋值和保存，修改课程的属性
     */
    public function update() {
        // 接收数据，取要更新的关键字信息
        $courseId = Request::instance()->post('id/d');

        // 获取当前对象
        $Course = Course::get($courseId);

        // 将修改后的课程属性内容保存
        if (!is_null($Course)) {
            if (!$this->saveCourse($Course)) {
                return $this->error('操作失败' . $Course->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }

    /**
     * 保存修改后的课程对象
     * @param $Course 将要修改的课程对象
     */
    private function saveCourse(Course &$Course) {
        // 将修改后的值赋值给Course对象
        $Course->usmix = Request::instance()->post('usmix');
        $Course->courseup = Request::instance()->post('courseup');
        $Course->begincougrade = Request::instance()->post('begincougrade');
        $Course->resigternum = Request::instance()->post('resigternum');

        // 更新或保存
        return $Course->validate(true)->save();
    }

    /**
     * 负责上课表现成绩的查看和修改
     */
    public function courseGrade() {
        try {
            // 接收课程id,并实例化课程对象
            $courseId = Request::instance()->param('courseId/d');
            $Course = Course::get($courseId);
            if ($teacherId = session('teacherId') !== $Course->teacher_id) {
                return $this->error('无此操作', Request::instance()->header('referer'));
            }

            // 实例化课程对象
            $Course = Course::get($courseId);
            // 定义每页展示两个数据
            $pageSize = 2;

            // 定制查询信息
            $num = Request::instance()->param('name/d');
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$courseId);
                $courseStudents = $courseStudents->join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate($pageSize);
                $Grades = Grade::where(['course_id' => $courseStudents[0]->course_id, 'student_id' => $courseStudents[0]->student_id])->paginate($pageSize);
                // 直接向V层传数据
                $this->assign('Grades', $Grades);
                return $this->fetch();
            }

            // 通过条件查询，获得该课程对应的上课成绩对象数组
            $Grades = Grade::where('course_id', '=', $courseId)->paginate($pageSize);
            
            // 向V层传数据
            $this->assign('Grades', $Grades);

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
