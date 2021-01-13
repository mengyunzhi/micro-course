<?php
namespace app\common\model;

class Menu { 
    public $title;
    private $href;
    public $action = 'index';
    public $controller;
    public function getClass() {
        // 增加判断是否为教师微信端，如果是则根据方法进行比较
        if (request()->controller() === 'Teacherwx' || request()->controller() === 'Student') {
            if (request()->action() === $this->action) {
                return 'active';
            } else {
                return '';
            }
        } else {
            if (request()->controller() === $this->controller) {
                return 'active';
            } else {
                return '';
            }
        }
    }

    public function getHref() {
        if (is_null($this->href)) {
            $this->href = url($this->controller . '/' . $this->action);
        }

        return $this->href;
    }
}
