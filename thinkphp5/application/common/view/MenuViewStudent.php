<?php
namespace app\common\view;
use app\common\model\Menu;

class MenuViewStudent {
	 private $viewHtml;
	 private $menus;
	 public function __construct() {
		 $signMenu = new Menu;
		 $signMenu->title = '签到记录';
		 $signMenu->action = 'afterSign';
		 $signMenu->controller = 'Student';

		 $passwordMenu = new Menu;
		 $passwordMenu->title = '密码修改';
		 $passwordMenu->action = 'changePassword';
		 $passwordMenu->controller = 'Student';

		 $this->menus = [$signMenu, $passwordMenu];
		 $this->viewHtml = view('index/wxstudent', 
		 	['menus' => $this->menus, 
		 	'title' => config('app.title')]);
	
	 }

	public function render() {
		 return $this->viewHtml->getContent();
	}
}