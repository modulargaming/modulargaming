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
class MG_Kostache_Layout extends Kohana_Kostache_Layout {

	/**
	 * @param string $layout layout file
	 * @return Kostache_Layout
	 */
	public static function factory($layout = 'layout')
	{
		return parent::factory($layout);
	}

	public function render($class, $template = NULL)
	{
		if ( ! empty($class->_partials))
		{
			$partials = array();
			foreach ($class->_partials as $key => $value)
			{
				$partials[$key] = file_get_contents(Kohana::find_file('templates', $value, 'mustache'));
			}
			$this->_engine->setPartials($partials);
		}

		return parent::render($class, $template);
	}

}
