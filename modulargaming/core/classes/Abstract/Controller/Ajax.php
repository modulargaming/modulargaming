<?php defined('SYSPATH') OR die('No direct script access.');
 
class Abstract_Controller_Ajax extends Controller {

	public function before()
	{
		parent::before();

		$this->response->headers('Content-Type', 'application/json');
	}

}
