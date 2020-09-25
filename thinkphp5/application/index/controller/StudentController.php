<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Student;//教师模型
use app\common\model\Course;
use think\Request;
use think\validate;


class StudentController extends Controller
{
    public function index()
    {

    	$id=Request::instance()->param('id/d');
    	$student=new student;
    	$course = new course;
       	$course = Course::where('course_id', 'like', '%' . $id . '%')->select();
       	$this->assign('student',$student);
       	return $this->fetch();

       
    }
}