<?php
namespace app\common\view;
use app\common\model\Menu;

class MenuView {
	 private $viewHtml;
	 private $menus;
	 public function __construct() {
		 $teacherMenu = new Menu;
		 $teacherMenu->title = '用户管理';
		 $teacherMenu->controller = 'AdminTeacher';

		 $termMenu = new Menu;
		 $termMenu->title = '学期管理';
		 $termMenu->controller = 'AdminTerm';

		 $classroomMenu = new Menu;
		 $classroomMenu->title = '教室管理';
		 $classroomMenu->controller = 'Classroom';

		 $this->menus = [$teacherMenu, $termMenu, $classroomMenu];
		 $this->viewHtml = view('index/menu', 
		 	['menus' => $this->menus, 
		 	'title' => config('app.title')]);
	
	 }

	public function render() {
		return '<h1>hello world this is menu</h1>';
		 return $this->viewHtml->getContent();
	}
}