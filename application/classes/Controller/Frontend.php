<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Frontend extends Controller {

	public function before()
	{
		$this->check_csrf();

		$this->auth = Auth::instance();
		$this->user = $this->auth->get_user();

	}

	public function after()
	{

	}

	private function check_csrf()
	{
		if (HTTP_Request::POST == $this->request->method())
		{
			$validation = Validation::factory($this->request->post())
				->rule('csrf', 'not_empty')
				->rule('csrf', 'Security::check');

			if ( ! $validation->check())
			{
				throw HTTP_Exception::factory(403, 'CSRF check failed!');
			}
		}
	}

} // End Frontend
