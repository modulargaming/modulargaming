<?php defined('SYSPATH') OR die('No direct script access.');
 
abstract class Abstract_Tab_Content {

	/**
	 * @var View_Tab_Content $view
	 */
	protected $view;

	public function render()
	{
		$renderer = Kostache::factory();
		return $renderer->render($this->view);
	}

}
