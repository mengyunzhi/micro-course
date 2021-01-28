<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;
use app\common\model\Term;
use app\common\model\Grade;
use app\common\model\ClassCourse;
use app\common\model\ClassDetail;
use Env;
use PHPExcel_IOFactory;
use PHPExcel;
use think\Controller;

/**
 * 课程的增删改查操作
 */
class CourseController extends IndexController {
    /**
     * 负责教师对应课程的展示
     */
    public function index() {
        //接受传来的ID值
        $id = session('teacherId');
        $name = Request::instance()->param('name');

        //通过接受的id来实例化Teacher
        $Teacher = Teacher::get($id);
        $Term = Term::get(['state' => 1]);
        $TermTmp = Term::get(['state' => 0]);
        $TermTmp->delete();

        // 增加判断是否当前处于学期激活中
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

        $Grade = new GradeController;
        //按条件查询数据并调用分页
        $courses = $Grade->getCourses($termId, $id, $name)->paginate();

        // 获取所有的学期信息
        $terms = Term::all();

        //向V层传数据
        $this->assign('courses', $courses);
        $this->assign('Teacher', $Teacher);
        $this->assign('Term', $Term);
        $this->assign('terms', $terms);
        //取回打包后的数据
        $htmls=$this->fetch();
        //将数据返回给用户
        return $htmls;
    }

    /**
     * 课程的新增方法，可通过Excel批量导入学生信息
     */
    public function add() {
        // 获取教师id
        $id = Request::instance()->param('id');
        // 查询处于激活的学期
        $Term = Term::get(['state' => 1]);
        // 判断当前是否有已被激活的学期
        if (is_null($Term)) {
            return $this->error('当前未处于学期中', Request::instance()->header('referer'));
        }

        // 实例化教师对象      
        $Teacher = Teacher::get($id);
        $Course= new Course();
        $Course->name = '';

        $this->assign('Course', $Course);
        $this->assign('Term', $Term);
        $this->assign('Teacher', $Teacher);

        //调用edit模板
        return $this->fetch('edit');
    }

    /**
     * 负责修改课程基本信息(上课初始成绩)
     */
    public function courseedit() { 
        // 获取课程id，并进行实例化  
        $courseId = Request::instance()->param('id/d');
        $Course=Course::get($courseId);
        if(is_null($Course)) {
            return $this->error('不存在Id为:' . $courseId . '的课程');
        }

        // 增加判断恶意修改情况
        $Course->checkTeacher();

        $this->assign('Course', $Course);
        return $this->fetch();
    }

    /**
     * 课程修改的赋值和保存
     */
    public function update() {
        // 获取当前老师和课程ID,并实例化课程对象
        $teacherId = session('teacherId');
        $courseId = Request::instance()->param('courseId');
        if (is_null($Course = Course::get($courseId))) {
            return $this->error('不存在ID为' . $courseId . '的记录');
        }

        // 调用V层方法，判断权限
        $Course->checkTeacher();
        
        // 更新课程信息(上课表现初始成绩、最高上课表现成绩、签到占比，签到一次占比)
        $Course->name = Request::instance()->post('name');
        if (!$Course->validate()->save()) {
            return $this->error('课程信息更新发生错误：' . $Course->getError());
        }

        // Excel表的导入
        $uploaddir = 'data/';
        // $uploaddir = "";
        $name = time() . $_FILES["userfile"]["name"];
        // dump($name);
        $uploadfile = $uploaddir . $name;
        $href = $uploaddir . $name;
        if ($_FILES['userfile']['size'] !== 0) {
            $unImportNumber = 0;
            if (!$this->excel($_FILES['userfile']['tmp_name'], $Course, $unImportNumber)) {
                return $this->error('文件上传失败');
            }
            // 成功新增课程，但是要返回未导入人数
            if ($unImportNumber === 0) {
                $this->computeGrades($courseId);
                return $this->success('新增课程和学生成功', url('index'));
            } else {
                $this->computeGrades($courseId);
                return $this->error('课程新增成功,未成功导入人数' . $unImportNumber . '个', url('index'));
            }
        } else {
            return $this->success('更新成功', url('index'));
        }
    }

    /**
     * 负责编辑课程从而导入学生名单时，对学生成绩的计算
     * @param courseId 课程对应的id
     */
    public function computeGrades($courseId)
    {
        // 首先获取该课程对应的所有上课课程
        $classCourses = ClassCourse::where('course_id', '=', $courseId)->select();
        // 首先获取该课程对应的中间表
        $courseStudents = CourseStudent::where('course_id', '=', $courseId)->select();

        // 判断中间表和上课次数是否为空，如果为空说明不需要计算学生成绩
        if (is_null($courseStudents)) {
            return true;
        }
        if (is_null($classCourses)) {
            return true;
        }

        // 如果上课详情和中间表不为空，此时要对上过课的学生进行加减分
        foreach ($classCourses as $ClassCourse) {
            // 根据上课课程id获取上课详情对象数组
            $classDetails = ClassDetail::where('class_course_id', '=', $ClassCourse->id)->select();
            if (sizeof($classDetails) !== 0) {
                foreach ($classDetails as $ClassDetail) {
                    // 首先定制判定条件，判断当前上课详情是否是课程名单学生的
                    $que = array(
                        'student_id' => $ClassDetail->id,
                        'course_id' => $ClassCourse->course_id
                    );
                    $Grade = Grade::get($que);

                    // 如果要加签到成绩必须满足在上课名单中，同时小于签到时间
                    if (!is_null($Grade)) {
                        if ($ClassDetail->create_time < $ClassCourse->sign_deadline_time) {
                            $Grade->resigternum ++;
                        }
                        // 如果要加上课表现成绩，只需在上课名单中
                        $Grade->coursegrade += $ClassDetail->aod_num;

                        // 成绩加完后，直接计算总成绩
                        $Grade->getAllgrade();
                    }
                }
            }
        }
    }

    /**
     * 课程的删除方法
     */
    public function delete() {
        try {
            // 实例化请求类
            $Request = Request::instance();

            // 获取教师id
            $teacherId = session('teacherId');
            
            // 获取课程id
            $courseId = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (0 === $courseId) {
                throw new \Exception('未获取到ID信息', 1);
            }

            // 获取要删除的对象
            $Course = Course::get($courseId);

            // 要删除的对象存在
            if (is_null($Course)) {
                throw new \Exception('不存在id为' . $courseId . '的课程，删除失败', 1);
            }

            $Course->checkTeacher();

            // 获取该课程对应中间表和成绩并删除
            $que = array('course_id' => $Course->id);
            if (Grade::where($que)->delete() === false) {
                return $this->error('成绩信息删除失败', Request::instance()->header('referer'));
            }
            if (CourseStudent::where($que)->delete() === false) {
                return $this->error('中间表信息删除失败', Request::instance()->header('referer'));
            }
            $classCourses = ClassCourse::where($que)->select();
            foreach ($classCourses as $ClassCourse) {
                if (!is_null($ClassCourse)) {
                    // 如果上课课程非空，删除对应的上课详情
                    if (!is_null(ClassDetail::where('class_course_id', '=', $ClassCourse->id)->select())) {
                        if (ClassDetail::where('class_course_id', '=', $ClassCourse->id)->delete() === false) {
                            return $this->error('上课详情删除失败', Request::instance()->header('referer'));
                        }
                    }
                    $ClassCourse->delete();
                }
            }
            // if (!$grades->delete()) {
            //     return $this->error('成绩信息删除失败', Request::instance()->header('referer'));
            // }
            // if (!$courseStudents->delete()) {
            //     return $this->error('中间表信息删除失败', Request::instance()->header('referer'));
            // }

            // 删除课程对象对象
            if (!$Course->delete()) {
                return $this->error('删除失败:' . $Course->getError());
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

        // 进行跳转 
        return $this->success('删除成功', $Request->header('referer')); 
    }

    /**
     * save课程 + 文件导入部分
     * 上传文件
     */
    public function fileUpload() {
        // 接收课程信息，并进行保存
        $Course = new Course();
        $Course->name = Request::instance()->post('name');
        $Course->teacher_id = Request::instance()->post('id');
        $Course->term_id = Request::instance()->post('term_id');
        $Course->student_num = 0;
        $Course->resigternum = 0;
        $Course->usmix = 50;
        $Course->courseup = 100;
        $Course->begincougrade = 0;

        if (!$Course->validate(true)->save()) {
            return $this->error('课程保存失败：' . $Course->getError());
        }
        // Excel表的导入
        $uploaddir = 'data/';
        // $uploaddir = "";
        $name = time() . $_FILES["userfile"]["name"];
        // dump($name);
        $uploadfile = $uploaddir . $name;
        /* dump($uploadfile);
         echo '<pre>';
         print_r($_FILES);*/
         /*dump($_FILES['userfile']);
         dump($_FILES['userfile']['size']);*/
       /* if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            return $this->success('新增课程成功', url('index'));
        }*/
         
        //$href 文件存储路径
        $href = $uploaddir . $name;
        if ($_FILES['userfile']['size'] !== 0) {
            $unImportNumber = 0;
            if (!$this->excel($_FILES['userfile']['tmp_name'], $Course, $unImportNumber)) {
                $Course->delete();
                return $this->error('文件上传失败');
            }

            // 成功新增课程，但是要返回未导入人数
            if ($unImportNumber === 0) {
                return $this->success('新增课程和学生成功', url('index'));
            } else {
                return $this->error('课程新增成功,未成功导入人数' . $unImportNumber . '个', url('index'));
            }
        } else {
            return $this->success('新增课程成功', url('index'));
        }
        
  }

   /**
    * 文件导入部分
    * 将Excel存入数据库
    * @param href 文件存储路径
    * @param Course 保存的课程对象
    * @param unImportNumber 未导入的学生人数
    */
    public function excel($href, $Course, &$unImportNumber) {
        /** Include path **/
        require_once dirname(__FILE__) . '/../PHPExcel/IOFactory.php';

        $inputFileName = $href;
       
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
       /* dump($sheetData);
        dump(sizeof($sheetData[1]));
        dump();
        die();*/

        // 将学生表中的数据存入数据库
        if (sizeof($sheetData[1]) !== 3) {
            if ($this->getCols($sheetData[1]) !== 3) {
                $Course->delete();
                return $this->error('学生上传失败,请参照模板上传', Request::instance()->header('referer'));
            }
        }
        if ($sheetData[1]["A"] != "序号" || $sheetData[1]["B"] != "姓名" || $sheetData[1]["C"] != "学号") {
            $Course->delete();
            return $this->error('文件格式与模板格式不相符', Request::instance()->header('referer'));
        }
        foreach ($sheetData as $sheetDataTemp) {
            $flag = 0;
            if ($sheetDataTemp['B'] !== '姓名') {
                // 定制查询信息
                $que = array(
                    'name' => $sheetDataTemp['B'],
                    'num' => $sheetDataTemp['C']
                );
                $StudentTmp = Student::get($que);

                //如果数据库中已经存在该学生，则只需新增中间表,否则新增学生信息并新增数据表
                // 新增中间表并保存,同时新增成绩
                $CourseStudent = new CourseStudent();
                if (is_null($StudentTmp)) {
                    $Student = new Student();
                    $Student->name = $sheetDataTemp["B"];
                    $Student->num = $sheetDataTemp["C"];
                    if ($Student->validate()->save()) {
                        $CourseStudent->student_id = $Student->id;
                        $flag = $Student->id;

                        // 新增成绩保存
                        if (!$this->saveGrade($Student, $Course)) {
                            return $this->error('课程-学生-成绩信息保存失败', url('Course/add'));
                        }
                    }
                } else {
                    // 首先增加判断当前课程中是否已经有该学生
                    $queTmp = array(
                        'course_id' => $Course->id,
                        'student_id' => $StudentTmp->id
                    );
                    if (is_null($CourseStudentTmp = CourseStudent::get($queTmp))) {
                        $CourseStudent->student_id = $StudentTmp->id;
                        $flag = $StudentTmp->id;
                        // 新增成绩保存
                        if (!$this->saveGrade($StudentTmp, $Course)) {
                            return $this->error('课程-学生-成绩信息保存失败', url('Course/add'));
                        }
                    }
                }
                if ($flag !== 0) {
                    $CourseStudent->course_id = $Course->id;
                    if (!$CourseStudent->save()) {
                        return $this->error('课程-学生信息保存失败', url('Course/add'));
                    }
                    // 课程对应学生数量加一
                    $Course->student_num++;
                } else {
                    $unImportNumber++;
                }
            }
        }

        if (!$Course->save()) {
            return $this->success('学生人数更新失败', Request::instance()->header('referer'));
        }
        return true;
    }

    /**
     * 获取模板的总列数
     * @param $sheetData 表格第一行
     */
    public function getCols($sheetData)
    {
        $i = 'A';
        $count = 0;
        while (!is_null($sheetData[$i])) {
            $i++;
            $count++;
        }
        return $count;
    }

    /**
     * 模板下载
     */
    public function templateDownload() {
        /** Include PHPExcel */
        require_once dirname(__FILE__) . '/../PHPExcel.php';


        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                                     ->setLastModifiedBy("Maarten Balliauw")
                                     ->setTitle("Office 2007 XLSX Test Document")
                                     ->setSubject("Office 2007 XLSX Test Document")
                                     ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                                     ->setKeywords("office 2007 openxml php")
                                     ->setCategory("Test result file");


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', '序号')
                    ->setCellValue('B1', '姓名')
                    ->setCellValue('C1', '学号');
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('模板');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
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

    /**
     * 新增成绩
     * @param Student 对应的学生对象
     * @param Course 对应的课程对象
     */
    public function saveGrade($Student, $Course) {
        // 新建成绩对象,并对新建对象进行赋值操作
        $Grade = new Grade();
        $Grade->student_id = $Student->id;
        $Grade->course_id = $Course->id;
        $Grade->coursegrade = $Course->begincougrade;
        $Grade->usgrade = 0;
        $Grade->resigternum = 0;
        $Grade->allgrade = $Grade->usgrade * $Course->usmix / 100 + $Grade->coursegrade * (1 - $Course->usmix / 100);
        return $Grade->save();
    }
}