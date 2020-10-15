<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Teacher;//教师模型
use think\Request;
use think\validate;
class TeacherController extends Controller
{
    public function index()
    {
        try {
            $pageSize = 5; // 每页显示5条数据

            // 实例化Teacher
            $Teacher = new Teacher; 
            // $Teacher = array_reverse($Teacher);
            // 调用分页
            $teachers = $Teacher->order('id desc')->paginate($pageSize);

            // $teachers = array_reverse($teachers);
            // 向V层传数据
            $this->assign('teachers', $teachers);

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


    public function insert(){
        $message = ' ';//tips
        try{
            //接收传入数据
        $postData = Request::instance()->post();    


        //实例化Teacher空对象
        $Teacher=new Teacher();

        //为对象赋值
        $Teacher->name=$postData['name'];
        $Teacher->username=$postData['username'];
        $Teacher->sex=$postData['sex'];
        $Teacher->email=$postData['email'];
        
        
        //新增对象至数据表
        $result = $Teacher->validate(true)->save();
        //反馈结果
        var_dump($result);
        var_dump($Teacher);
        if(true==$result)
        {return $this->success('用户' . $Teacher->name . '新增成功。', url('index'));}
        else{
            return '新增失败：'.$Teacher->getError();
         }
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;
            // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return $this->error($message);
    }
    public function add()
    {
        // 实例化
        $Teacher = new Teacher;

        // 设置默认值
        $Teacher->id = 0;
        $Teacher->name = '';
        $Teacher->teacher_id = '';
        $this->assign('Teacher', $Teacher);

        // 调用edit模板
        return $this->fetch('edit');
    }
    public function edit()
    {
        try {
            // 获取传入ID
            $id = Request::instance()->param('id/d');

            // 判断是否成功接收
            if (is_null($id) || 0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }
            
            // 在Teacher表模型中获取当前记录
            if (null === $Teacher = Teacher::get($id))
            {
                // 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
                $this->error('系统未找到ID为' . $id . '的记录');
            } 
            
            // 将数据传给V层
            $this->assign('Teacher', $Teacher);

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
     private function saveTeacher(Teacher &$Teacher) 
    {
        // 写入要更新的数据
        $Teacher->name = input('post.name');
        $Teacher->teacher_id = input('post.teacher_id');

        // 更新或保存
        return $Teacher->validate(true)->save();
    }



    //保存数据
     public function save()
    {
        // 实例化
        $Teacher = new Teacher;

        // 新增数据
        if (!$this->saveTeacher($Teacher)) {
            return $this->error('操作失败' . $Teacher->getError());
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }





    public function delete(){
        try{
            //实例化请求类
            $Request = Request::instance();
            //获取pathinfo传入的id值
            $id = Request::instance()->param('id/d');//    ‘d’表示将数据化为整形
            if (is_null($id)||0===$id) {
            throw new Exception("未获取到ID信息", 1);
            }
            //获取要删除的对象
            $Teacher = Teacher::get($id);
            //删除对象不存在
            if (is_null($Teacher)) 
            {
                throw new Exception("不存在此ID的教师", 1);
            
            }
            //删除对象
            if (!$Teacher->delete()) {
                return $this->error('删除失败'.$Teacher->       getError());
            }//获取内置异常时直接向上抛出，交php处理 
        }catch (\think\Exception\HttpResponseException $e) {
            throw $e;
         // 获取到正常的异常时，输出异常
         } catch (\Exception $e) {
            return $e->getMessage();
         }
      // 进行跳转
         return $this->success('删除成功', $Request->header('referer'));
    }








    //更新数据
    public function update()
    {
        //接收数据
        $teacher = Request::instance()->post();


        //将数据存入Teacher表
        $Teacher = new Teacher();
        $message = 'success'; 

        //依据状态定制提示信息
        //关于异常https://blog.csdn.net/weixin_30952103/article/details/96788600
        try{
            if(false===$Teacher->validate(true)->isUpdate(true)->save($teacher)) {
            $message = 'fail'.$Teacher->getError();
            }
        }
        catch(\Exception $e){
            $message = 'fail'.$e->getMessage();
            
        }


        if(!is_null($Teacher))
        {
            if(!$this->save($Teacher))
            {
                return $this->error('操作失败'.$Teacher->getError());
            }
        }else{
            return $this->error('当前记录不存在');
        }

        //跳转到index触发器
        return $this->success('操作成功', url('index'));
    }






    public function test()
    {
    try {

        throw new    \Exception("Error Processing Request", 1);
        return $this->error("系统发生错误");
     // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
         } catch (\think\Exception\HttpResponseException $e) {
        throw $e;
     // 获取到正常的异常时，输出异常
         } catch (\Exception $e) {
        return $e->getMessage();
         }
    }
    
}