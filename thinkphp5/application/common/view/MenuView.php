<?php
namespace app\common\view;
use app\common\model\Menu;

class MenuView {
	 private $viewHtml;
	 private $menus;
	 public function __construct() {
		 $TermMenu = new Menu;
		 $TermMenu->title = '学期管理';
		 $TermMenu->controller = 'Term';

		 $UserMenu = new Menu;
		 $UserMenu->title = '用户管理';
		 $UserMenu->controller = 'AdminTeacher';

		 $ClassroomMenu = new Menu;
		 $ClassroomMenu->title = '教室管理';
		 $ClassroomMenu->controller = 'Classroom';

		 $SeatmapMenu = new Menu;
		 $SeatmapMenu->title = '座位图模板管理';
		 $SeatmapMenu->controller = 'SeatMap'
		 ;

		 $this->menus = [$TermMenu, $UserMenu, $ClassroomMenu, $SeatmapMenu];
		 $this->viewHtml = view('index/menu', 
		 	['menus' => $this->menus, 
		 	'title' => config('app.title')]);
	
	 }

	public function render() {
		 return $this->viewHtml->getContent();
	}
}