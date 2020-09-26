<?php
namespace app\common\model;
use think\Model;    // 使用前进行声明
/**
 * Admin
 */
class Admin extends Model
{
    /**
     * 用户登录
     * @param  string $username 用户名
     * @param  string $password 密码
     * @return bool   成功返回true，失败返回false。
     */
    static public function login($username, $password)
    {
        // 验证用户是否存在
        $map = array('username' => $username);
        $Admin = self::get($map);
        
        if (!is_null($Admin)) {
            // 验证密码是否正确
            if ($Admin->checkPassword($password)) {
                // 登录
                session('AdminId', $Admin->getData('id'));
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
        //为什么在这里可以用$this-> ?
        if ($this->getData('password') === $this->encryptPassword($password))
        {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 密码加密算法
     */
    static public function encryptPassword($password)
    {   
        if (!is_string($password)) {
            throw new \RuntimeException("传入变量类型非字符串，错误码2", 2);
        }

        // 实际的过程中，我还还可以借助其它字符串算法，来实现不同的加密。
        return sha1(md5($password) . 'mengyunzhi');
    }
    
}