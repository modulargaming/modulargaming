<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base controller for frontend controllers.
 * 
 * @package    Modular Gaming
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class Controller_Frontend extends Controller {

	protected $auth; // Auth instance.
	protected $user; // Current logged in user.

	protected $protected = FALSE; // Require user to be logged in.
	protected $view; // View to render.

	protected $layout = 'layout';

	public function before()
	{
		$this->check_csrf();

		$this->auth = Auth::instance();
		$this->user = $this->auth->get_user();

		if ($this->protected == TRUE AND $this->auth->logged_in() == FALSE)
		{
			throw HTTP_Exception::Factory(403, 'Login to access this page!');
		}

	}

	public function after()
	{
		if ($this->view)
		{
			$renderer = Kostache_Layout::factory();
			$renderer->set_layout($this->layout);
			$this->response->body($renderer->render($this->view));
		}
	}

	private function check_csrf()
	{
		if ($this->request->method() == HTTP_Request::POST)
		{
			$validation = Validation::factory($this->request->post())
				->rule('csrf', 'not_empty')
				->rule('csrf', 'Security::check');

			if ( ! $validation->check())
			{
				throw HTTP_Exception::Factory(403, 'CSRF check failed!');
			}
		}
	}

} // End Frontend
