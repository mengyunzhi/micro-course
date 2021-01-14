<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Grade;
use Env;
use PHPExcel_IOFactory;
use PHPExcel;


/**
 * 总成绩查看页面和负责修改总成绩
 */
class GradeLookController extends IndexController {
     public function index() {
        try {
            // 获取课程信息,同时如果有查询信息，获取对应学生的学号
            $courseId = Request::instance()->param('id');
            $num = Request::instance()->param('name');

            //实例化课程,并增加判断是否为当前教师
            if (is_null($Course = Course::get($courseId))) {
                return $this->error('课程信息不存在', Request::instance()->header('referer'));
            }
            if ($teacherId = session('teacherId') !== $Course->teacher_id) {
                return $this->error('无此操作', Request::instance()->header('referer'));
            }
            $pageSize = 2; // 每页显示2条数据
            $Students = $Course->Students;

            $Grades = Grade::where('course_id', '=',  $courseId . '%')->paginate($pageSize);

            // 获取查询信息，并实现查找对应学生的成绩
            $num = Request::instance()->param('name/d');
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$courseId);
                $courseStudents = $courseStudents->join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate($pageSize);
                if (sizeof($courseStudents) !== 0) {
                    $Grades = Grade::where(['course_id' => $courseStudents[0]->course_id, 'student_id' => $courseStudents[0]->student_id])->paginate($pageSize);
                    // 直接向V层传数据
                    $this->assign('grades', $Grades);
                    $this->assign('students', $Students);
                    $this->assign('course', $Course);
                    return $this->fetch();
                } else {
                    return $this->error('查找不存在', Request::instance()->header('referer'));
                }
            } else {
                // 向V层传数据
                $this->assign('students', $Students);
                $this->assign('grades', $Grades);
                $this->assign('course', $Course);

                // 取回打包后的数据
                $htmls = $this->fetch();

                // 将数据返回给用户
                return $htmls;
            }

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
            $courseId = Request::instance()->param('id/d');
            $num = Request::instance()->param('name');

            // 实例化课程
            $Course = Course::get($courseId);
            $pageSize = 2; // 每页显示5条数据

            // 通过课程获取学生对象数组
            $Students = $Course->Students;

            // 按照总成绩递减的方式查找成绩对象数组，并通过降序的方式展示
            $Grades = Grade::order('allgrade desc')->where('course_id', '=', $courseId)->paginate($pageSize);
            
            // 获取查询信息，并实现查找对应学生的成绩
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$courseId);
                $courseStudents = $courseStudents->join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate($pageSize);
                $Grades = Grade::where(['course_id' => $courseStudents[0]->course_id, 'student_id' => $courseStudents[0]->student_id])->paginate($pageSize);
                // 直接向V层传数据
                $this->assign('grades', $Grades);
                $this->assign('students', $Students);
                $this->assign('course', $Course);
                return $this->fetch();
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
            $pageSize = 2; // 每页显示5条数据

            // 根据课程获取该班对应的学生对象数组
            $Students = $Course->Students;

            $Grades = Grade::order('allgrade asc')->where('course_id', '=', $courseId)->paginate($pageSize);

            // 获取查询信息，并实现查找对应学生的成绩
            if(!empty($num)) {
                $courseStudents = CourseStudent::alias('a')->where('a.course_id','=',$courseId);
                $courseStudents = $courseStudents->join('student s','a.student_id = s.id')->where('s.num','=',$num)->paginate($pageSize);
                $Grades = Grade::where(['course_id' => $courseStudents[0]->course_id, 'student_id' => $courseStudents[0]->student_id])->paginate($pageSize);
                // 直接向V层传数据
                $this->assign('grades', $Grades);
                $this->assign('students', $Students);
                $this->assign('course', $Course);
                return $this->fetch();
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

    /**
     * 将未签到的学生信息以Excel的形式输出
     */
    public function fileExportGrade() {
        // 接收课程id并通过课程id获取该课程对应的所有成绩
        $courseId = Request::instance()->param('courseId');
        $Course = Course::get($courseId);
        $Grades = Grade::where('course_id', '=', $courseId)->select();

        // PHPExcel.php文件的引入
        require_once dirname(__FILE__) . '/../PHPExcel.php';

        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Yunzhi Meng")//创立者
                                     ->setLastModifiedBy("yunzhi")//最后修改者
                                     ->setTitle("Office 2007 XLSX Test Document")//文件名，以下的不用动
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");

        // 添加数据
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '序号')
                    ->setCellValue('B1', '姓名')
                    ->setCellValue('C1', '学号')
                    ->setCellValue('D1', '考勤成绩')
                    ->setCellValue('E1', '表现成绩')
                    ->setCellValue('F1', '总成绩');
                    
                    // 利用foreach循环将数据库中的数据读出，下面仅仅是将学生表的数据读出
                    $count = 2;
                    foreach ($Grades as $Grade) {
                        // Miscellaneous glyphs, UTF-8
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A' . $count, $count-1)
                                    ->setCellValue('B' . $count, $Grade->Student->name)
                                    ->setCellValue('C' . $count, $Grade->Student->num)
                                    ->setCellValue('D' . $count, $Grade->getUsgrade())
                                    ->setCellValue('E' . $count, $Grade->coursegrade)
                                    ->setCellValue('F' . $count, $Grade->getAllgrade());
                        $count++;
                    }

        // 导出的Excel表的表名，不是文件名
        $objPHPExcel->getActiveSheet()->setTitle('上课情况汇总');

        // 必须要有，否则导出的Excel用不了，设定活跃的表是哪个，设定的活跃表是表0
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit; 
    }
}
