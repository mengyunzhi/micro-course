<?php
// 简单的原理重复记： namespace说明了该文件位于application\common\model 文件夹
namespace app\common\model;
use think\Model;    //  导入think\Model类
use think \ db \ Query;

/**
 * Teacher 教师表
 */
  
// 我的类名叫做Teacher,对应的文件名为Teacher.php.该类继承了Model类,Model我们在文件头中,提前使用use进行了导入
class Teacher extends Model
{
	/**
     * 获取要显示的创建时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }
    /**
     * 获取要显示的更新时间
     * @param  int $value 时间戳
     * @return string  转换后的字符串
     * @author panjie <panjie@yunzhiclub.com>
     */
    public function getUpdateTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

	 static public function login($username, $password)
    {
        // 验证用户是否存在
        $map = array('username' => $username);
        $Teacher = self::get($map);
        if (!is_null($Teacher)) {
            // 验证密码是否正确
            if ($Teacher->checkPassword($password)) {
                // 登录
                session('teacherId', $Teacher->getData('id'));
                return true;
            }
        }
        return false;
    }

    /**
     * 验证密码是否正确
     * @param  string $password 密码
     * @return bool           
     */
	public function checkPassword($password)
	{
		if ($this->getData('password') === $this::encryptPassword($password))
		{
			return true;
		} else {
			return false;
		}
	}


	static public function encryptPassword($password)
	{
		if(!is_string($password))
		{
			throw new \RuntimeException("传入变量类型非字符串，错误码2", 2);
			
		}
		 return sha1(md5($password) . 'mengyunzhi');
	}

	static public function logOut()
	{
		session('teacherId',null);
		return true;
	}

	static public function isLogin()
	{
		$teacherId=session('teacherId');
		//isset()和is_null是一对反义词
		if(isset($teacherId))
		{
            return $teacherId;
		}
		else {
			return false;
		}
	}

    /**
     * 获取随机密码
     */
    public function getRandomPassword()
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';

        //打乱字符串
        $randStr = str_shuffle($str);

        //substr(string,start,length);返回字符串的一部分
        $rands= substr($randStr, 0, 6);
        return $rands;
    } 
}