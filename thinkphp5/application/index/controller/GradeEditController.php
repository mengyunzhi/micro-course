<?php
namespace app\index\controller;
use app\common\model\Course;
use app\common\model\Klass;
use think\Request;
use app\common\model\CourseStudent;
use app\common\model\Student;
use app\common\model\Teacher;


/**
 * 
 */
class GradeController extends IndexController
{
     public function index()
    {
        try {
            // 获取查询信息
            $id = Request::instance()->param('id');
            
            //实例化课程
            $teacher =Teacher::get($id);

            // 定制查询信息
            if (!empty($id)) {

            }

            //获取该teacher对应的课程
            $courses = Course::where('teacher_id', 'like', '%' . $id . '%')->paginate(5);
            $this->assign('teacher', $teacher);
            $this->assign('courses', $courses);

            // 取回打包后的数据
            $htmls = $this->fetch();

            // 将数据返回给用户
            return $htmls;

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }


    
}
