<?php
namespace app\index\controller;
use think\Controller;
class FunctionController
{
	 /**
	 * 导入excel表数据
     */
    public function addExcel()
    {
        //接收前台文件
        $ex = $_FILES['file'];
        //重设置文件名
        $filename = time() . substr($ex['name'], stripos($ex['name'], '.'));
        $path = 'excel/' . $filename;//设置移动路径
        move_uploaded_file($ex['tmp_name'], $path);
        //表用函数方法 返回数组
        $exfn = $this->_readExcel($path); // 读取内容
        $this->upload_file($exfn, $path); // 上传数据 
    }
    public function _readExcel($path)
    {
        //引用PHPexcel 类
        include_once('util/PHPExcel.php');
        include_once('util/PHPExcel/IOFactory.php');//静态类
        $type = 'Excel2007';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版
        $xlsReader = PHPExcel_IOFactory::createReader($type);
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($path);
        //开始读取上传到服务器中的Excel文件，返回一个二维数组
        $dataArray = $Sheets->getSheet(0)->toArray();
        return $dataArray;
    }
    private function upload_file($data, $path)
    {
        global $db;
        $arr = array();
        array_push($arr, $data[0]);
        //删除第一项
        unset($data[0]);
        $sql = 'insert into media_platform (user,phone,passwd,head,nickname,platform) values (?,?,?,?,?,?)';
        $stmt = $db->prepare($sql);
        foreach ($data as $v) {
            $result = $stmt->execute(array($v[0] ? $v[0] : '', $v[1] ? $v[1] : '', $v[2] ? $v[2] : '', $v[3] ? $v[3] : '', $v[4] ? $v[4] : '', $v[6] ? $v[6] : ''));
            // $stmts->execute(array($v[6] ? $v[6] : ''));
            if (!$result) {
                array_push($arr, $v);
            }
        }
        echo json_encode($arr);
        unlink($path); // 上传完文件之后删除文件，避免造成垃圾文件的堆积
    }
}