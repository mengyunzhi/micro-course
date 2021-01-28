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

class Test {
   $name = "";
}


$test1 = new Test();
$test2 = new Test();

echo $test1 === $test2;