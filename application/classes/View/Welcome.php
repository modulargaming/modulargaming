<?php defined('SYSPATH') or die('No direct script access.');

class View_Welcome
{
	public $title = 'Welcome';

	public function csrf()
	{
		return Security::token();
	}

}
