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
use Env;
use PHPExcel_IOFactory;
use PHPExcel;

/**
 * 
 */
class CourseController extends IndexController
{
     
    public function index()
    {
        //接受传来的ID值
        $id = session('teacherId');
        $name = Request::instance()->param('name');

        //通过接受的id来实例化Teacher
         $Teacher = Teacher::get($id);
         $Term = Term::get(['state' => 1]);
        //查询的情况时

        // 调用父类构造函数(必须)
        parent::__construct();
        //验证用户是否登录
        if(!Teacher::isLogin())
        {
            return $this->error('plz login first',url('Login/index'));
        }

        $course = Course::get($id);

        $pageSize=5;//每页显示5条数据

        //实例化Course
        $Course = new Course;
        
        //打印$Teacher 至控制台
        trace($Teacher,'debug');

        //按条件查询数据并调用分页
         $courses = $Course->where('teacher_id', '=', $id)->where('term_id', '=', $Term->id)->paginate($pageSize);

         if (!empty($name))
         {
            $courses = Course::where('name', 'like', '%' . $name . '%')->paginate();
         }
 

        //向V层传数据
        $this->assign('courses',$courses);
        $this->assign('Teacher',$Teacher);
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

        // 实例化教师对象      
        $Teacher = Teacher::get($id);
        $Course=new Course;
        $Course->name = '';

        $this->assign('Course',$Course);
        $this->assign('Term',$Term);
        $this->assign('Teacher',$Teacher);

        //调用edit模板
        return $this->fetch('edit');
    }

    /**
     * 负责修改课程基本信息(上课初始成绩)
     */
    public function courseedit() { 
        // 获取课程id，并进行实例化  
        $courseId=Request::instance()->param('id/d');
        $Course=Course::get($courseId);
        if(is_null($Course)){
            return $this->error('不存在Id为:' . $courseId . '的课程');
        }

        $this->assign('Course',$Course);
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

        // 更新课程信息(上课表现初始成绩、最高上课表现成绩、签到占比，签到一次占比)
        $Course->name = Request::instance()->post('name');
        $Course->resigternum = Request::instance()->post('resigternum');
        $Course->usmix = Request::instance()->post('usmix');
        $Course->courseup = Request::instance()->post('courseup');
        $Course->begincougrade = Request::instance()->post('begincougrade');
        if (is_null($Course->validate(true)->save())) {
            return $this->error('课程信息更新发生错误：' . $Course->getError());
        }
        return $this->success('更新成功', url('index'));
    }

     public function delete()
    {
        try {
            // 实例化请求类
            $Request = Request::instance();
            
            // 获取get数据
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }

            // 获取要删除的对象
            $Course = Course::get($id);

            // 要删除的对象存在
            if (is_null($Course)) {
                throw new \Exception('不存在id为' . $id . '的课程，删除失败', 1);
            }

            // 删除对象
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
     * 文件导入部分
     * 上传文件
     */
    public function file1() {
        // 接收课程信息，并进行保存
        $Course = new Course();
        $Course->name = Request::instance()->post('name');
        $Course->teacher_id = Request::instance()->post('id');
        $Course->term_id = Request::instance()->post('term_id');
        $Course->student_num = 0;
        $Course->resigternum = 0;
        $Course->usmix = Request::instance()->param('usmix');
        $Course->courseup = Request::instance()->param('courseup');
        $Course->begincougrade = Request::instance()->param('begincougrade');

        // 新增数据并验证。验证类
        if (!$Course->validate(true)->save()) {
            return $this->error('保存错误：' . $Course->getError());
        }

        // Excel表的导入
        $uploaddir = '/data/';
        // $uploaddir = "";
        $name = time() . $_FILES["userfile"]["name"];
        // dump($name);
        $uploadfile = $uploaddir . $name;
        // dump($uploadfile);
        // echo '<pre>';
        // print_r($_FILES);
        // dump($_FILES['userfile']['tmp_name']);
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            
        } else {
            echo "Possible file upload attack!\n";
        }

    //$href 文件存储路径
   $href = $uploaddir . $name;
    if(!$this->excel($href, $Course)) {
        return $this->error('文件上传失败');
    }
    return $this->success('文件上传并且学生信息保存成功', url('add'));
  }

   /**
    * 文件导入部分
    * 将Excel存入数据库
    * @param href 文件存储路径
    * @param Course 保存的课程对象
    */

  public function excel($href, $Course) {
        /** Include path **/
        require_once dirname(__FILE__) . '/../PHPExcel/IOFactory.php';

        $inputFileName = $href;
       
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        // 将学生表中的数据存入数据库
        $count = 1;
        if($sheetData[1]["A"] != "序号" || $sheetData[1]["B"] != "姓名" || $sheetData[1]["C"] != "学号"  || $sheetData[1]["D"] != "性别" || $sheetData[1]["E"] != "邮件" ) {
            return $this->error('文件格式与模板格式不相符', url('file'));
        }
        $count = 0;
        foreach ($sheetData as $sheetDataTemp) {
            if($count !== 0) {
                // 定制查询信息
                $que = array(
                    'name' => $sheetDataTemp['B'],
                    'num' => $sheetDataTemp['C']
                );
                $StudentTmp = Student::get($que);
                // 如果数据库中已经存在该学生，则只需新增中间表,否则新增学生信息并新增数据表
                if (is_null($StudentTmp)) {
                    $Student = new Student();
                    $Student->name = $sheetDataTemp["B"];
                    $Student->num = $sheetDataTemp["C"];
                    $Student->sex = $sheetDataTemp["D"];
                    $Student->email = $sheetDataTemp["E"];
                    $Student->save(); 
                }
                // 新增中间表并保存,同时新增成绩 
                $CourseStudent = new CourseStudent();
                if (is_null($StudentTmp)) {
                    $CourseStudent->student_id = $Student->id;
                    // 新增成绩保存
                    if (!$this->saveGrade($Student, $Course)) {
                        return $this->error('课程-学生-成绩信息保存失败', url('Course/add'));
                    }
                } else {
                    $CourseStudent->student_id = $StudentTmp->id;
                    // 新增成绩保存
                    if (!$this->saveGrade($StudentTmp, $Course)) {
                        return $this->error('课程-学生-成绩信息保存失败', url('Course/add'));
                    }
                }
                $CourseStudent->course_id = $Course->id;
                if (!$CourseStudent->save()) {
                    return $this->error('课程-学生信息保存失败', url('Course/add'));
                }
            }
            $count++;
            // 课程对应学生数量加一
            $Course->student_num++;
        }

        if (!$Course->save()) {
            return $this->success('操作失败', url('Course/add'));
        } 
        return 1;
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
                    ->setCellValue('C1', '学号')
                    ->setCellValue('D1', '性别')
                    ->setCellValue('E1', '邮件');
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
        $Grade->allgrade = $Grade->usgrade * $Course->usmix / 100 + $Grade->coursegrade * (1 - $Course->usmix/100);
        return $Grade->save();
    }
}
