<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Grade;
use app\common\model\Teacher;
use app\common\model\Term;

/**
 * 成绩管理，负责总成绩的展示和编辑部分
 */
class GradeController extends IndexController {
     public function index() {
        try {
            // 获取查询信息
            $teacherId =session('teacherId');
            $termId = input('termId');
            $Term = Term::get($termId);
            if(is_null($termId)) {
                $Term = Term::get(['state' => 1]);
                if(is_null($Term)) {
                    $termId = "";
                } else {
                $termId = $Term->id;
                }
            }
            $name = Request::instance()->param('name');

            //获取一切符合条件的课程
            $Courses = $this->getCourses($termId, $teacherId, $name)->paginate();

            
            $terms = Term::all();
            $this->assign('terms', $terms);
            $this->assign('Term', $Term);
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
     * 获取相应学期的课程
     * @param $termId 相应学期id
     * @param $teacherId 相应教师id
     * @param $name 查询时需要的名字
     */
    public function getCourses($termId, $teacherId, $name) {
        $courses = Course::where('term_id', '=', $termId)->where('teacher_id', '=', $teacherId);
        if(!is_null($name)) {
            $courses = $courses->where('name', 'like', '%' . $name . '%');
        }
        return $courses;
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
        return $this->success('成绩占比修改成功', url('Gradelook/index?id=' . $Course->id));
    }

    /**
     * 保存修改后的课程对象
     * @param $Course 将要修改的课程对象
     */
    private function saveCourse(Course &$Course) {
        // 首先获取该课程对应的所有学生成绩
        if (is_null($Course)) {
            return $this->error('学生成绩传送失败', Request::instance()->header('referer'));
        }
        $grades = Grade::where('course_id', '=', $Course->id)->select();

        // 获取上课表现成绩对应的初始成绩的初值之差
        $subtract = Request::instance()->post('begincougrade') - $Course->begincougrade;

        // 将修改后的值赋值给Course对象
        $Course->usmix = Request::instance()->post('usmix');
        $Course->courseup = Request::instance()->post('courseup');
        $Course->begincougrade = Request::instance()->post('begincougrade');

        // 对更改成绩后的成绩重新计算
        $number = sizeof($grades);
        for ($i = 0; $i < $number; $i ++) {
            $grades[$i]->coursegrade = $grades[$i]->coursegrade + $subtract;
            if ($grades[$i]->coursegrade > $Course->courseup) {
                $grades[$i]->coursegrade = $Course->courseup;
            }
            $grades[$i]->getAllgrade();
        }

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
            //实例化课程,并增加判断是否为当前教师
            if (is_null($Course = Course::get($courseId))) {
                return $this->error('课程信息不存在', Request::instance()->header('referer'));
            }            
            if ($teacherId = session('teacherId') !== $Course->teacher_id) {
                return $this->error('无此操作', Request::instance()->header('referer'));
            }

            // 实例化课程对象
            $Course = Course::get($courseId);
            // 定义每页展示两个数据

            // 定制查询信息
            $num = Request::instance()->param('name/d');
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$courseId);
                $courseStudents = $courseStudents->join('student s','a.student_id = s.id')->where('s.num', '=', $num)->paginate();
                if (sizeof($courseStudents) !== 0) {
                    $Grades = Grade::where(['course_id' => $courseStudents[0]->course_id, 'student_id' => $courseStudents[0]->student_id])->paginate();
                    // 直接向V层传数据
                    $this->assign('Grades', $Grades);
                    return $this->fetch();
                } else {
                    return $this->error('查找不存在', Request::instance()->header('referer'));
                }
            }

            // 通过条件查询，获得该课程对应的上课成绩对象数组
            $Grades = Grade::where('course_id', '=', $courseId)->paginate();
            
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