<?php
namespace app\common\view;
use app\common\model\Menu;

class MenuView {
	// private $viewHtml;
	// private $menus;
	// public function __construct() {
		// $courseMenu = new Menu;
		// $courseMenu->title = '课程管理';
		// $courseMenu->controller = 'Teacher';

		// $onClassMenu = new Menu;
		// $onClassMenu->title = '上课管理';
		// $onClassMenu->controller = 'OnClass';

		// $gradeMenu = new Menu;
		// $gradeMenu->title = '成绩管理';
		// $gradeMenu->controller = 'Grade';

		// $this->menus = [$courseMenu, $onClassMenu, $gradeMenu];
		// $this->viewHtml = view('index/menu', 
		// 	['menus' => $this->menus, 
		// 	'title' => config('app.title')]);
	
	// }

	public function render() {
		return '<h1>hello world this is menu</h1>';
		// return $this->viewHtml->getContent();
	}
}