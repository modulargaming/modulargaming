<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   Tab
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Tabs {

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
