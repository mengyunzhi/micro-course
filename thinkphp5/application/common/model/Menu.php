<?php
namespace app\common\model;

class Menu { 
    public $title;
    private $href;
    public $action = 'index';
    public $controller;
    public function getClass() {
        // 增加判断是否为教师微信端，如果是则根据方法进行比较
        if (request()->controller() === 'Teacherwx') {
            if (request()->action() === $this->action) {
                return 'active';
            } else {
                return '';
            }
        } else {
            // 如果不是首先判断是不是三个子类，最后判断直属
            if (request()->controller() === 'Student') {
                if ($this->controller === 'Course') {
                    return 'active';
                } else {
                    return '';
                }
            } 
            if (request()->controller() === 'Gradelook') {
                if ($this->controller === 'Grade') {
                    return 'active';
                } else {
                    return '';
                }
            }
            if (request()->controller() === 'Coursegrade' || request()->controller() === 'InClass') {
                if ($this->controller === 'PreClass') {
                    return 'active';
                } else {
                    return '';
                }
            }

            // 第三种是直属的
            if (request()->controller() === $this->controller) {
                return 'active';
            } else {
                return '';
            }
        }
    }

    /**
     * 获取学生微信端class
     */
    public function getStudentClass() {
        // 增加判断是否为教师微信端，如果是则根据方法进行比较
        if (request()->action() === $this->action) {
            return 'active';
        } else {
            return '';
        }
    }

    public function getHref() {
        if (is_null($this->href)) {
            $this->href = url($this->controller . '/' . $this->action);
        }

        return $this->href;
    }
}
