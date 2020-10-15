<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;//教师模型
use app\common\model\Course;//教师模型
use think\Request;
use think\validate;


class courseController extends Controller
{
    
    public function index()
    {

    	$id=Request::instance()->param('id/d');
    	if(!empty($id))
    	{
    		$Teacher=new Teacher;
    	    $course = new course;
       	    $course = Course::where('teacher_id', 'like', '%' . $id . '%')->select();
       	    $pageSize = 5;
       	    $this->assign('course',$course);
       	    return $this->fetch();
    	}
    }




    //插入
    public function insert()
    {
        // 实例化Term空对象
        $Course = new Course();
        
        // 为对象的属性赋值
        $Course->id=1;
        $Course->name=$postData['name'];
        $Course->teacher_id=$id;
     
        // 执行对象的插入数据操作
        $Course->save();
        return $Course->name . '成功增加至数据表中。新增ID为:' . $Course->id;
    }



    //添加
	public function add()
    {
        // 实例化
        $Course = new Course;

        // 设置默认值
        $Course->id = 1;
        $Course->name = '';
        $this->assign('Course', $Course);

        // 调用edit模板
        return $this->fetch('edit');
    }




    //编辑
     public function edit()
    {
        try {
            // 获取传入ID
            $id = Request::instance()->param('id/d');

            // 判断是否成功接收
            if (is_null($id) || 0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }
            
            // 在Course表模型中获取当前记录
            if (null === $Course = Course::get($id))
            {
                // 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
                $this->error('系统未找到ID为' . $id . '的记录');
            } 
            
            // 将数据传给V层
            $this->assign('Course', $Course);

            // 获取封装好的V层内容
            $htmls = $this->fetch();

            // 将封装好的V层内容返回给用户
            return $htmls;

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
    }
    public function delete()
    {
        try {
            // 实例化请求类
            $Request = Request::instance();
            
            // 获取get数据
            $id = Request::instance()->param('id/d');
            
            // 判断是否成功接收
            if (0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }

            // 获取要删除的对象
            $Course = Course::get($id);

            // 要删除的对象存在
            if (is_null($Course)) {
                throw new \Exception('不存在id为' . $id . '的课程，删除失败', 1);
            }

            // 删除对象
            if (!$Course->delete()) {
                return $this->error('删除失败:' . $Course->getError());
            }

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 

        // 进行跳转 
        return $this->success('删除成功', $Request->header('referer')); 
    }
    /**
     * 对数据进行保存或更新
     * @param    Course                  &$Course 教师
     * @return   bool                             
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-10-24T15:24:29+0800
     */
    private function saveCourse(Course &$Course) 
    {
        // 写入要更新的数据
        $Course->name = input('post.name');

        // 更新或保存
        return $Course->validate(true)->save();
    }
     public function save()
    {
        // 实例化
        $Course = new Course;

        // 新增数据
        if (!$this->saveCourse($Course)) {
            return $this->error('操作失败' . $Course->getError());
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }



    public function update()
    {

        // 接收数据，取要更新的关键字信息
        $id = Request::instance()->post('id/d');

        // 获取当前对象
        $Course = Course::get($id);

        if (!is_null($Course)) {
            if (!$this->saveCourse($Course)) {
                return $this->error('操作失败' . $Course->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
         return $this->success('操作成功', url('index'));
       
    }
}