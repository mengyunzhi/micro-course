<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Term;//教师模型
use think\Request;
use think\validate;
use app\common\model\Teacher;
use app\common\model\Course;
use app\common\model\Student;
use app\common\model\CourseStudent;
class TermController extends AdminJudgeController
{
	public function index()
	{
		try {
			// 获取查询信息
            $name = Request::instance()->get('name');


            $pageSize = 5; // 每页显示5条数据

            // 实例化Term
            $Term = new Term; 
            if(!Teacher::isLogin()) {
                return $this->error('plz login first',url('Login/index'));
            }

            // 定制查询信息
            if (!empty($name)) {
                $Term->where('name', 'like', '%' . $name . '%');
            }

            // 按条件查询数据并调用分页
            $terms = $Term->order('id desc')->paginate($pageSize, false, [
                'query'=>[
                    'name' => $name,
                    ],
                ]);

            // 向V层传数据
            $this->assign('terms', $terms);

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
	public function insert()
    {
        // 实例化Term空对象
        $Term = new Term();
        
        // 为对象的属性赋值
        $Term->id=0;
        $Term->name=$postData['name'];
		$Term->ptime=$postData['ptime'];
     
        // 执行对象的插入数据操作
        $Term->save();
        return $Term->name . '成功增加至数据表中。新增ID为:' . $Term->id;
    }
	public function add()
    {
        // 实例化
        $Term = new Term;

        // 设置默认值
        $Term->id = 0;
        $Term->name = '';
        $Term->ptime = '';
        $Term->ftime = '';
        $this->assign('Term', $Term);

        // 调用edit模板
        return $this->fetch('edit');
    }
     public function edit()
    {
        try {
            // 获取传入ID
            $id = Request::instance()->param('id/d');
            $Term = Term::all();

            // 判断是否成功接收
            if (is_null($id) || 0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }
            
            // 在Term表模型中获取当前记录
            if (null === $Term = Term::get($id))
            {
                // 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
                $this->error('系统未找到ID为' . $id . '的记录');
            } 
            
            // 将数据传给V层
            $this->assign('Term', $Term);

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
    public function is_open(){
         try {
            $Request = Request::instance();
            
            // 获取传入ID
            $id = Request::instance()->param('id/d');

            // 判断是否成功接收
            if (is_null($id) || 0 === $id) {
                throw new \Exception('未获取到ID信息', 1);
            }
            
            // 在Term表模型中获取当前记录
            if (null === $Term = Term::get($id))
            {
                // 由于在$this->error抛出了异常，所以也可以省略return(不推荐)
                $this->error('系统未找到ID为' . $id . '的记录');
            } 
            $Term = new Term;
            $Term = Term::all();
            $Term1 = Term::get($id);
            // 如果该学期已激活，则冻结该学期，反之激活
            if($Term1->state === 1) {
                $Term = $Term1;
                $Term->state = 0;
            } else {
                foreach ($Term as $Term1) {
                $Term1->state = 0;
                $Term1->save();
            }
            $Term = Term::get($id);
            Term::$Term_id = $id;
            $Term->state = '1';
            }
            $Term->save();

        // 获取到ThinkPHP的内置异常时，直接向上抛出，交给ThinkPHP处理
        } catch (\think\Exception\HttpResponseException $e) {
            throw $e;

        // 获取到正常的异常时，输出异常
        } catch (\Exception $e) {
            return $e->getMessage();
        } 
        return $this->success('状态修改成功', $Request->header('referer')); 
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
            $Term = Term::get($id);

            // 要删除的对象存在
            if (is_null($Term)) {
                throw new \Exception('不存在id为' . $id . '的教师，删除失败', 1);
            }

            //删除与本学期相关的课程信息和课程学生关联表信息
            $courses = Course::where('term_id', '=', $id)->select();
            foreach ($courses as $Course ) {
                $AdminCourseController = new AdminCourseController;
                $AdminCourseController->deleteCourseStudent($Course->id);
                if(!$Course->delete()) {
                    return $this->error('删除本学期课程失败');
                }
            }

            // 删除对象
            if (!$Term->delete()) {
                return $this->error('删除失败:' . $Term->getError());
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
     * @param    Term                  &$Term 教师
     * @return   bool                             
     * @author 梦云智 http://www.mengyunzhi.com
     * @DateTime 2016-10-24T15:24:29+0800
     */
    private function saveTerm(Term &$Term) 
    {
        // 写入要更新的数据
        $Term->name = input('post.name');
        $Term->ptime = input('post.ptime');
        $Term->ftime = input('post.ftime');
        $Term1 = Term::where('state', '=', 1)->select();
        if(empty($Term1)) {
            $Term->state = 1;
        }

        // 更新或保存
        return $Term->validate(true)->save();
    }
     public function save()
    {
        // 实例化
        $Term = new Term;

        // 新增数据
        if (!$this->saveTerm($Term)) {
            return $this->error('操作失败' . $Term->getError());
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', url('index'));
    }
    public function update()
    {
        // 接收数据，取要更新的关键字信息
        $id = Request::instance()->post('id/d');

        // 获取当前对象
        $Term = Term::get($id);

        if (!is_null($Term)) {
            if (!$this->saveTerm($Term)) {
                return $this->error('操作失败' . $Term->getError());
            }
        } else {
            return $this->error('当前操作的记录不存在');
        }
    
        // 成功跳转至index触发器
        return $this->success('操作成功', $_POST['httpref']);
    }
}