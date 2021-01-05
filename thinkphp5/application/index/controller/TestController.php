<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Europe/London');

/** PHPExcel_IOFactory */
include './PHPExcel/IOFactory.php';


$inputFileName = '__PUBLIC__/static/example1.xls';
echo 'Loading file ',pathinfo($inputFileName,PATHINFO_BASENAME),' using IOFactory to identify the format<br />';
$objPHPExcel = PHPExcel_IOFactory::load($inputFileName);


echo '<hr />';

$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
var_dump($sheetData);


?>
