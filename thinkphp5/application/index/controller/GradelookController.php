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
 * 总成绩查看页面和负责修改总成绩
 */
class GradeLookController extends IndexController {
     public function index() {
        try {
            // 获取课程信息,同时如果有查询信息，获取对应学生的学号
            $courseId = Request::instance()->param('id');
            $num = Request::instance()->param('name');

            // 实例化课程
            $Course = Course::get($courseId);
            $pageSize = 2; // 每页显示5条数据
            $Students = $Course->Students;

            $Grades = Grade::where('course_id', '=',  $courseId . '%')->paginate($pageSize);

            // 获取查询信息，并实现查找对应学生的成绩
            if (!empty($num)) {
                // 通过学号，获取该学生对象
                $Students = Student::where('num', '=', $num)->select();

                // 定制查询信息，通过课程id和学生对象信息获取对应的成绩
                $que = array(
                    "course_id" => $courseId,
                    "student_id" => $Students[0]->id
                );
                $Grades = Grade::where($que)->paginate($pageSize);
            }

            // 向V层传数据
            $this->assign('students', $Students);
            $this->assign('grades', $Grades);
            $this->assign('course', $Course);

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
     * 正序输出成绩
     */
    public function listindex() {
        try {
            // 获取课程信息,同时如果有查询信息，获取对应学生的学号
            $courseId = Request::instance()->param('id');
            $num = Request::instance()->param('name');

            // 实例化课程
            $Course = Course::get($courseId);
            $pageSize = 5; // 每页显示5条数据

            // 通过课程获取学生对象数组
            $Students = $Course->Students;

            // 按照总成绩递减的方式查找成绩对象数组，并通过降序的方式展示
            $Grades = Grade::order('allgrade desc')->where('course_id', 'like', '%' . $courseId . '%')->paginate($pageSize);
            
            // 获取查询信息，并实现查找对应学生的成绩
            if (!empty($num)) {
                // 通过学号和课程号，获取该学生对象
                $Students = Student::where('num', '=', $num)->select();

                // 定制查询信息，通过课程id和学生对象信息获取对应的成绩
                $que = array(
                    "course_id" => $courseId,
                    "student_id" => $Students[0]->id
                );
                $Grades = Grade::where($que)->paginate($pageSize);
            }

            // 向V层传数据
            $this->assign('students', $Students);
            $this->assign('grades', $Grades);
            $this->assign('course', $Course);

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
     * 通过倒序的方式展示总成绩
     */
    public function upindex() {
        try {
            // 获取课程信息,同时如果有查询信息，获取对应学生的学号
            $courseId = Request::instance()->param('id');
            $num = Request::instance()->param('name');
            
            // 实例化课程
            $Course = Course::get($courseId);
            $pageSize = 5; // 每页显示5条数据

            // 根据课程获取该班对应的学生对象数组
            $Students = $Course->Students;

            $Grades = Grade::order('allgrade asc')->where('course_id', 'like', '%' . $courseId . '%')->paginate($pageSize);

            // 获取查询信息，并实现查找对应学生的成绩
            if (!empty($num)) {
                // 通过学号，获取该学生对象
                $Students = Student::where('num', '=', $num)->select();

                // 定制查询信息，通过课程id和学生对象信息获取对应的成绩
                $que = array(
                    "course_id" => $courseId,
                    "student_id" => $Students[0]->id
                );
                $Grades = Grade::where($que)->paginate($pageSize);
            }

            // 向V层传数据
            $this->assign('students', $Students);
            $this->assign('grades', $Grades);
            $this->assign('course', $Course);

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
     * 对学生总成绩进行编辑，可编辑上课表现成绩和签到成绩
     */
    public function edit() {
        // 接收传入的成绩对象id
        $gradeId = Request::instance()->param('id/d');
        // 判断是否存在为此id的记录
        if(is_null($Grade = Grade::get($gradeId))) {
            return $this->error('未找到ID为' . $gradeId . '的记录');
        }

        // 向V层传值
        $this->assign('Grade', $Grade);
        
        // 接收从V层渲染后的效果并展示
        return $this->fetch();
    }

    /**
     * 对修改后的成绩进行保存
     */
    public function update() {
        // 接收数据，取要更新的关键字信息
        $gradeId = Request::instance()->post('id/d');

        // 获取成绩对象,并根据成绩获取对应的课程对象
        $Grade = Grade::get($gradeId);
        $Course = $Grade->Course;

        if (!is_null($Grade)) {
            if (!$this->saveGrade($Grade, $Course, true)) {
                return $this->error('操作失败' . $Grade->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index?id=' . $Course->id));
    }
    
    /**
     * 保存修改的成绩
     * @param $Grade 将要被修改的成绩对象
     * @param $Course 该成绩对应的课程
     * @param $isUpdate 判断是否是更新数据
     */
    private function saveGrade(Grade &$Grade, Course &$Course, $isUpdate = false) {
        // 写入要更新的数据
        $Grade->resigternum = Request::instance()->post('resigternum');
        if($Grade->resigternum > $Course->resigternum) {
            $Grade->resigternum = $Course->resigternum;
        }
        $Grade->usgrade = $Grade->resigternum / $Course->resigternum * 100;
        $Grade->coursegrade = Request::instance()->post('coursegrade');
        $Grade->allgrade = $Grade->usgrade * $Grade->Course->usmix / 100 + $Grade->coursegrade * (100 - $Grade->Course->usmix) / 100;
        // 更新或保存
        return $Grade->validate(true)->save();
    }
}
