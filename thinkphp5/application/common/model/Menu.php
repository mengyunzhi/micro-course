<?php
namespace app\common\model;

class Menu { 
	public $title;
	private $href;
	public $action = 'index';
	public $controller;
	public function getClass()
	{
		if (request()->controller() === $this->controller) {
			return 'active';
		} else {
			return '';
		}
	}

	public function getHref()
	{
		if (is_null($this->href)) {
			$this->href = url($this->controller . '/' . $this->action);
		}

		return $this->href;
	}
}
