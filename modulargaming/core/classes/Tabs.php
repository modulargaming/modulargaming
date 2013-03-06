<?php defined('SYSPATH') OR die('No direct script access.');

class Tabs {

	private $tabs = array();

	public function add_tab(Tab $tab)
	{
		$this->tabs[] = $tab;
	}

	public function get_all()
	{
		return $this->tabs;
	}

	public function render()
	{
		$view = new View_Tabs;
		$view->tabs = $this->tabs;

		$renderer = Kostache::factory();
		return $renderer->render($view);
	}
}
