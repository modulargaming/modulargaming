<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/Core
 * @category   Kostache
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Kostache_Email extends Kostache_Layout {

	private $type;

	public function set_type($type)
	{
		$type = '.'.$type;
		$this->type = $type;
		$this->set_layout('Email/'.$this->_layout.$type);
	}

	/**
	 * If text use Class.text.mustache, if html use Class.html.mustache
	 */
	public function render($class, $template = NULL)
	{
		if ($this->type != NULL AND $template == NULL)
		{
			$template = explode('_', get_class($class));
			array_shift($template);
			$template = implode('/', $template);
			$template .= $this->type;
		}

		return parent::render($class, $template);
	}

}
