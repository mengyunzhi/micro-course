<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\validate;

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
	public function file() {
		return $this->fetch();
	}
	public function file1() {
    $uploaddir = 'd:/data/';
    $uploadfile = $uploaddir . basename($_FILES['userfile']['name']);


    echo '<pre>';
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "File is valid, and was successfully uploaded.\n";
    } else {
        echo "Possible file upload attack!\n";
    }

    echo 'Here is some more debugging info:';
    print_r($_FILES);

    print "</pre>";
  }
}
