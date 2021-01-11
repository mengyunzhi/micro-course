<?php
namespace app\common\view;
use app\common\model\Menu;

class MenuViewWx {
	 private $viewHtml;
	 private $menus;
	 public function __construct() {
		 $courseMenu = new Menu;
		 $courseMenu->title = '课程查看';
		 $courseMenu->action = 'course';
		 $courseMenu->controller = 'WxTeacher';

		 $courseGradeMenu = new Menu;
		 $courseGradeMenu->title = '上课表现';
		 $courseGradeMenu->action = 'wxaod';
		 $courseGradeMenu->controller = 'WxTeacher';

		 $signMenu = new Menu;
		 $signMenu->title = '签到修改';
		 $signMenu->action = 'signChange';
		 $signMenu->controller = 'WxTeacher';

		 $this->menus = [$courseMenu, $courseGradeMenu, $signMenu];
		 $this->viewHtml = view('index/menu', 
		 	['menus' => $this->menus, 
		 	'title' => config('app.title')]);
	 }

	public function render() {
		 return $this->viewHtml->getContent();
	}
}