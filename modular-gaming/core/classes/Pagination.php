<?php defined('SYSPATH') OR die('No direct script access.');

class Pagination extends Kohana_Pagination {

	/**
	 * Renders the pagination links.
	 *
	 * @param   mixed   $view  View object
	 * @return  string  Pagination output (HTML)
	 */
	public function render($view = NULL)
	{
		// Automatically hide pagination whenever it is superfluous
		if ($this->config['auto_hide'] === TRUE AND $this->total_pages <= 1)
			return '';

		if ($view === NULL)
		{
			// Use the view class from config
			$refl = new ReflectionClass('View_'.$this->config['view']);
			$view = $refl->newInstanceArgs();
		}

		$view->pagination = $this;

		/*
		if ( ! $view instanceof View_Pagination)
		{
			// Load the view file
			$view = View::factory($view);
		}
		*/

		$renderer = Kostache::factory();
		return $renderer->render($view);

		// Pass on the whole Pagination object
		//return $view->set(get_object_vars($this))->set('page', $this)->render();
	}

}
