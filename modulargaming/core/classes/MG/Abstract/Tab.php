<?php defined('SYSPATH') OR die('No direct script access.');

abstract class MG_Abstract_Tab {

	/**
	 * @var Abstract_View_Tab $view
	 */
	protected $view;

	public function render()
	{
		$renderer = Kostache::factory();
		return $renderer->render($this->view);
	}

}
