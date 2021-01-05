<?php

namespace app\index\controller;
use think\Controller;
use think\Db;
use Env;
use PHPExcel_IOFactory;
use app\common\model\Excel;
use app\common\model\File;

class ExcelController extends Controller{

    public function excel() {
        error_reporting(E_ALL);
		set_time_limit(0);

		date_default_timezone_set('Europe/London');


		/** Include path **/
		set_include_path(get_include_path() . PATH_SEPARATOR . '../../../Classes/');

		/** PHPExcel_IOFactory */
		include 'PHPExcel/IOFactory.php';
		$inputFileName = '/example1.xls';
		echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
		$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


		echo '<hr />';

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		dump($sheetData);

    }
}