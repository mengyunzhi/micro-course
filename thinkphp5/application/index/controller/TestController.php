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
		if(isset($_POST["submit"])) {
                $url='localhost';
                $username='root';
                $password='';
                $conn=mysqli_connect($url,$username,$password,"location");
          if(!$conn){
          die('Could not Connect My Sql:' .mysqli_error());
		  }
          $file = $_FILES['file']['tmp_name'];
          $handle = fopen($file, "r");
          $c = 0;
          while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
          	$fname = $filesop[0];
          	$lname = $filesop[1];
          	$sql = "insert into excel(fname,lname) values ('$fname','$lname')";
          	$stmt = mysqli_prepare($conn,$sql);
          	mysqli_stmt_execute($stmt);

         	$c = $c + 1;
           }

            if($sql){
               echo "sucess";
             } 
		 	else
		 	{
            	echo "Sorry! Unable to impo.";
          	}

		}
	}
}
