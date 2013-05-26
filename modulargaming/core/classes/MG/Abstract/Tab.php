<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
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
