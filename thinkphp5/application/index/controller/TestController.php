<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\validate;
use app\common\model\Excel;
use app\common\model\File;
use app\common\model\Student;
use Env;
use PHPExcel_IOFactory;
use PHPExcel;

class TestController extends IndexController
{
	public function index()
	{
		$i=1;
		for($i;$i<5;$i++)
		{
			echo "数字为".$i;
		}
		$this->assign('i',$i);
		return $this->fetch();
	}

    /**
     * 文件导入部分
     * 上传文件（渲染）
     */
	public function file() {
		return $this->fetch();
	}

    /**
     * 文件导入部分
     * 上传文件
     */
	public function file1() {
    $uploaddir = '/data/';
    $name = basename($_FILES['userfile']['name']) . time();
    var_dump($name);
    $uploadfile = $uploaddir . $name;


    echo '<pre>';
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
    }

    //$href 文件存储路径
    $href = '/data/' . $name;
    
    echo 'Here is some more debugging info:';
    print_r($_FILES);
    $this->excel($href);
    print "</pre>";
  }

   /**
    * 文件导入部分
    * 将Excel存入数据库
    * @param $href 文件存储路径
    */
  public function excel() {
        /** Include path **/
       
        dirname(__FILE__) . '/../PHPExcel/IOFactory.php';
        $inputFileName = '/data';
       
        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);




        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
        dump($sheetData);

        // 将学生表中的数据存入数据库
        $count = 1;
        if($sheetData[1]["A"] != "序号" || $sheetData[1]["B"] != "姓名" || $sheetData[1]["C"] != "学号"  || $sheetData[1]["D"] != "性别" || $sheetData[1]["E"] != "邮件" ) {
            return $this->error('文件格式与模板格式不相符', url('file'));
        }
        $count = 0;
        foreach ($sheetData as $sheetData1 ) {
            if($count != 0) {
                $Student = new Student;
                $Student->name = $sheetData1["B"];
                $Student->num = $sheetData1["C"];
                $Student->sex = $sheetData1["D"];
                $Student->email = $sheetData1["E"];
                $Student->save();
            }
            $count++;
        }
    }

    /**
     * 模板下载
     */
    public function templateDownload() {
        /** Include PHPExcel */
        require_once dirname(__FILE__) . '/../../PHPExcel.php';
        die();
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
     * 文件导出（渲染）
     */
    public function fileExport() {
        return $this->fetch();
    }

    /**
     * 文件导出
     */
    public function fileExport1() {
        require_once dirname(__FILE__) . '/../PHPExcel.php';


        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties

        $objPHPExcel->getProperties()->setCreator("Liting Chen")//创立者
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
                    ->setCellValue('D1', '性别')
                    ->setCellValue('E1', '邮件');
                    
                      

                    // 利用foreach循环将数据库中的数据读出，下面仅仅是将学生表的数据读出
                    $students = Student::all();
                    $count = 2;
                    foreach ($students as $Student) {
                        // Miscellaneous glyphs, UTF-8
                        $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A' . $count, $count-1)
                                    ->setCellValue('B' . $count, $Student->name)
                                    ->setCellValue('C' . $count, $Student->num)
                                    ->setCellValue('D' . $count, $Student->sex)
                                    ->setCellValue('E' . $count, $Student->email);
                        $count++;
                    }
       

        // 导出的Excel表的表名，不是文件名
        $objPHPExcel->getActiveSheet()->setTitle('成绩');

        //必须要有，否则导出的Excel用不了，设定活跃的表是哪个，设定的活跃表是表0
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
