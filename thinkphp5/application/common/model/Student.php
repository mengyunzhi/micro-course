<?php
namespace app\common\model;
use think\Model;
use app\common\model\CourseStudent;
use app\common\model\Grade;

class Student extends Model
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
     * 获取对应课程的成绩
     * @param courseId 对应的课程id
     */
    public function Grade($courseId)
    {
        // 根据课程id和学生id获取对应的成绩
        $que = array(
            'student_id' => $this->id,
            'course_id' => $courseId
        );
        $Grade = Grade::get($que);
        return $Grade;
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

    public function Courses()
    {
        return $this->belongsToMany('Course',config('database.prefix').'course_student');
    }

    public function getNum()
    {

    }

    /**
     * 验证密码是否正确
     */
    public function checkPassword($password) {
        // 通过加密算法计算密码是否正确
        if ($this->getData('password') === $this::encryptPassword($password)) {
            return true;
        } else {
            return false;
        }
    }

    public function Course()
    {
        return $this->belongsTo('course');
    }
    
    //获取是否存在相关信息
     public function getIsChecked(Course &$Course)
    {
        // 取课程ID
        $studentId = (int)$this->id;
        $courseId = (int)$Course->id; 

        // 定制查询条件
        $map = array();
        $map['course_id'] = $courseId;
        $map['student_id'] = $studentId;

        // 从关联表中取信息
        $CourseStudent = CourseStudent::get($map);
        if (is_null($CourseStudent)) {
            return false;
        } else {
            return true;
        }
    }

    private $Course;

    /**
     * 获取对应的教师（辅导员）信息
     * @return Teacher 教师
     * @author <panjie@yunzhiclub.com> http://www.mengyunzhi.com
     */
    public function CourseStudents()
    {
        return $this->hasMany('CourseStudent');
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * 增加登陆判断
     * @param username 登录检验用到的用户名(学号)
     * @param password 登录检验用的密码
     * @param name 存在多个相同学号的额外判定条件
     */
    public static function login($username, $password, $name = null)
    {
        // 定制查询信息，判断是否存在username
        if (!is_null($name)) {
            $que = array(
                'username' => $username,
                'name' => $name
            );
        } else {
            $que = array(
                'username' => $username,
            );
        }

        $Student = Student::get($que);
        if (!is_null($Student)) {
            // 验证密码是否正确
            if ($Student->checkPassword($password)) {
                // 登录
                session('studentId', $Student->getData('id'));
                return true;
            }
        }
        return false;
    }

    /**
     * 设置学生登陆密码算法加密
     */
    public static function encryptPassword($password)
    {
        // 增加判断传入密码是否为字符串
        if (!is_string($password)) {
            throw new \RuntimeException("传入变量类型非字符串，错误码2", 2);
        }

        // 如果密码合格直接加密
        return sha1(md5($password) . 'mengyunzhi');
    }
}