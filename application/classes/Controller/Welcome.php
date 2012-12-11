<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		$renderer = Kostache_Layout::factory();
		$this->response->body($renderer->render(new View_Welcome));
	}

} // End Welcome
