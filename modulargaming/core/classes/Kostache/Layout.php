<?php defined('SYSPATH') OR die('No direct script access.');

class Kostache_Layout extends Kohana_Kostache_Layout {

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
