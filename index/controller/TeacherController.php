<?php
namespace app\index\controller;     // 该文件的位于application\index\controller文件夹

use think\Controller;               // 用于与V层进行数据传递
use think\Request;                  // 引用Request
use app\common\model\Teacher;       // 教师模型
use app\common\model\Course;

/**
 * 教师管理，继承think\Controller后，就可以利用V层对数据进行打包了。
 */
class TeacherController extends IndexController
{
    public function index()
    {
        //接受传来的ID值
         $id = Request::instance()->param(1);

         echo $id;

         //通过接受的id来实例化Teacher
         $Teacher=Teacher::get($id);
         //不是查询的情况时

             // 调用父类构造函数(必须)
        parent::__construct();
        //验证用户是否登录
        if(!Teacher::isLogin())
        {
            return $this->error('plz login first',url('Login/index'));
        }


        $pageSize=5;//每页显示5条数据

        
        
        //打印$Teacher 至控制台
        trace($Teacher,'debug');

        //按条件查询数据并调用分页
         $courses = Course::where('teacher_id', 'like', '%' . $id . '%')->paginate($pageSize, false, [
            'query'=>[
                'id' => $id,
                ],
            ]);
 

        //向V层传数据
        $this->assign('courses',$courses);
        $this->assign('Teacher',$Teacher);
        //取回打包后的数据
        $htmls=$this->fetch();
        //将数据返回给用户
        return $htmls;
        
    }

    /**
     * 插入新数据
     * @return   html                   
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-11-07T12:31:24+0800
     */
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

    /**
     * 新增数据交互
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-11-07T12:41:23+0800
     */
    public function add()
    {
        $Course=new Course;

        //设置默认值
        $Course->id=0;
        $Course->name='';
        
        $this->assign('Course',$Course);

        //调用edit模板
        return $this->fetch('edit');
    }
    

    /**
     * 删除
     * @return   跳转                   
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-11-07T13:52:07+0800
     */
    public function delete()
    {
        try{
            //实例化请求类
        $Request=Request::instance();
        // 获取get值
        $id = Request::instance()->param('id/d'); // /d 表示将数值转化为 整形

        //判断是否成功接收
        if (0 === $id) {
            throw new \Exception("未获取到ID信息", 1);
            
        }

        // 获取要删除的对象
        $Course = Course::get($id);

        // 要删除的对象不存在
        if (is_null($Course)) {
            throw new \Exception("不存在id为' . $id . '的课程，删除失败", 1);
            
        }

        // 删除对象
        if (!$Course->delete()) {
            return $this->error('删除失败:'.$Teacher->getError());
        }
    }
        //  获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        catch(\think\Exception\HttpResponseException $e){
            throw $e;
            //获取到正常的异常时，输出异常
        }catch(\Exception $e){
        return $e->getMessage();
    }
    //进行跳转
    return $this->success('删除成功',$Request->header('referer'));
}


    public function test()
    {
        try
        {
            throw new \Exception("Error Processing Request",1);
            return $this->error("系统发生错误");

            //获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理

        }  catch(\think\Exception\HttpResponseException $e)
        {
            throw $e;
            //获取到正常的异常时，输出异常
        } catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * 编辑
     * @return   html                   
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-11-07T13:52:29+0800
     */
    public function edit()
    {
        try
        {
        // 获取传入ID
        $id = Request::instance()->param('id/d');

        //判断是否成功接收
        if(is_null($id)||0===$id)
        {
            throw new \Exception("未获取到ID信息", 1);
        }

        // 在Teacher表模型中获取当前记录
        if (null===$Course=Course::get($id)) {
            //由于在$this->error抛出了异常，所以也可以省略return(不推荐)
            $this->error('系统未找到ID为'.$id.'的记录');
        } 
        
        // 将数据传给V层
        $this->assign('Course', $Course);

        // 获取封装好的V层内容
        $htmls = $this->fetch();

        // 将封装好的V层内容返回给用户
        return $htmls;

        //获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
    } catch(\think\Exception\HttpResponseException $e)
    {
        throw $e;
    } catch(\Exception $e)
    {
        return $e->getMessage();
    }
    }

    /**
     * 更新
     * @return                      
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-11-07T14:03:41+0800
     */
    public function update()
    {
        // 接收数据，取要更新的关键字信息
        $id = Request::instance()->post('id/d');

        // 获取当前对象
        $Course = Course::get($id);

        if (!is_null($Course)) {
            if (!$this->saveCourse($Course,true)) {
                return $this->error('操作失败' . $Course->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }



     private function saveCourse(Course &$Course, $isUpdate = false) 
    {
        // 写入要更新的数据
        $Course->name = Request::instance()->post('name');
        if (!$isUpdate) {
            
        }
        $Course->name = Request::instance()->post('name');
        // 更新或保存
        return $Course->validate(true)->save();
    }
}

