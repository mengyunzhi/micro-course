<?php
//呼叫Reader
require_once 'Spreadsheet/Excel/Reader/reader.php';
//建立 Reader
$data = new Spreadsheet_Excel_Reader();
//設定文字輸出編碼
$data->setOutputEncoding('GB2312');

//讀取Excel檔案
$data->read("c:/test.xls");
//$data->sheets[0]['numRows']為Excel行數
for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
  //$data->sheets[0]['numCols']為Excel列數
  for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
   //顯示每個單元格內容
   echo $data->sheets[0]['cells'][$i][$j];
  }
}
?>