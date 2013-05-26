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
class MG_Tab {

	/**
	 * @var String $name
	 */
	public $name;

	/**
	 * @var Abstract_Tab[] $content
	 */
	private $contents = array();

	public function __construct($name)
	{
		$this->name = $name;
	}

	public function add_content(Abstract_Tab $content)
	{
		$this->contents[] = $content;
	}

	public function render()
	{
		$content = "";
		foreach ($this->contents as $c)
		{
			$content .= $c->render();
		}
		return $content;
	}

}
