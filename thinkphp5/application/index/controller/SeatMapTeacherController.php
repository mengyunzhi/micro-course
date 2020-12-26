<?php
namespace app\index\controller;
use think\Controller;
use app\common\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\PreClass;
use app\common\model\Teacher;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\Seat;
class SeatMapTeacherController extends Controller {
    
    public function index() {
        
        return $this->fetch();
    }
    public function add() {
        return $this->fetch();
    }
    public function edit() {
        return $this->fetch();
    }
    public function template1() {
        return $this->fetch();
    }
    public function template2() {
        return  $this->fetch();
    }
    public function afterclass() {
        // 接收教室编号

        // 对该教室所有座位进行初始化，使其状态为未被坐

        // 需要跳转到course/index
    }
    
    public function test() {
        return $this->fetch();
    }
}