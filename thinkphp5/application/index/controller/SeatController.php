<?php 
namespace app\index\controller;
use think\Controller;
use app\common\model\Seat;
use think\Request;
use think\validate;
use phpqrcode\QRcode;
use app\common\model\Student;


/**
 * 
 */
class SeatController extends Controller 
{

	/**
	 * [index description]
	 * @Author   温宇航
	 * @DateTime 2020-10-09T08:30:22+0800
	 * @function 直接显示该教室的座位图
	 *           我要存很多的数据表吗？
	 *           或者说我在写数据表的时候是动态生成的？
	 * @return   [type]                   [description]
	 */
	public function index()
	{
		//接收传过来的

		//数据库条件查询，建议去网上找方法
		$Seat = new Seat;
		$Seats = $Seat->select();


		//向V层传数据,V层的接收没写好
		$this->assign('seats',$Seats);

		//取回打包后的数据
		$htmls = $this->fetch();

		//将数据返回给用户
		return $htmls;

	}





	/**
	 * [qrcode description]
	 * @Author   温宇航
	 * @Receive  收到学生信息与座位号参数
	 * @DateTime 2020-10-09T16:36:36+0800
	 * @function 传入参数完成二维码的生成，导航到seated
	 *           当然最后的希望是完成成批的输出二维码
	 * @param    string                   $level [description]
	 * @param    integer                  $size  [description]
	 * @return   [type]                          [description]
	 */
	public function qrcode($level = 'L', $size = 4)
	{
    	// 导入Phpqrcode插件文件require_once EXTEND_PATH.'phpqrcode/phpqrcode.php';
    
    	//设置url
    	$url1 = 'http://192.168.2.143/thinkphp5/public/index.php/index/seat/index.html';

    	// 容错级别
    	$errorCorrectionLevel = $level;
    	// 生成图片大小
    	$matrixPointSize = intval($size);
    	// 生成二维码图片
    	$object = new QRcode();
    	// 这个一定要加上，清除缓冲区
    	ob_end_clean();
    	// 第二个参数false的意思是不生成图片文件，如果你写上‘picture.png’则会在根目录下生成一个png格式的图片文件
    	$object->png($url1, false, $errorCorrectionLevel, $matrixPointSize, 2);


	}

	/**
	 * [seated description]
	 * @Author   温宇航
	 * @Receive  收到学生信息与座位号参数 
	 * @DateTime 2020-10-09T16:42:41+0800
	 * @function 完成扫码座位登录，改变数据库中的seated属性
	 * @return   [type]                   [description]
	 */
	public function seated()
	{

		//获取传入ID,以及openid
		$id = Request::instance()-> param('id/d');

		//此处需要获取输入的openid
		$open_id = Request::instance()->param('openid/d');

		if(is_null($Seat = $Seat::get($id))){
			return '此处无座,我想知道你是怎么到这里的';
		}

		//在Seat模型表中获取当前的记录
		
		$Seat->seated = 1;


		$Seat->openid = $openid;

		//完成，保存
		$Seat->save();

	}

	/**
	 * [checkstudent description]
	 * @Author   温宇航
	 * @DateTime 2020-10-10T09:26:32+0800
	 * @function 获取该座位的人员信息，返回学生姓名学号
	 *           接收座位ID，名字检查->check
	 *           在座位表里面存储学生的id
	 * @Receive  座位ID
	 * @return   [type]表单，包括当前就坐学生的姓名，班级，以及该座位的二维码               [description]
	 */
	public function check()
	{
		//获取传入ID
		$id = Request::instance()->param();
		$id = 2;

		//return $id;
		
		$seat = seat::get($id);
		$Student = Student::get($seat->getData('student_id')); 


		//var_dump($Student);
		//die();

		//向V层传数据；
		$this->assign('student',$Student);


		//$this->assign('png',$this->qrcode());
		

		//取回返回的数据
		$htmls = $this->fetch();




		//将数据返回给用户
		return $htmls;		
	}
}

