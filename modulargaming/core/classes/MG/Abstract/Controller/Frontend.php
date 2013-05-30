<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base controller for frontend controllers.
 *
 * @package    MG/Core
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class MG_Abstract_Controller_Frontend extends Controller {

	/**
	 * @var Abstract_View view to render
	 */
	protected $view = NULL; // View to render.

	/**
	 * @var string layout file.
	 */
	protected $layout = 'layout';

	/**
	 * Run CSRF check and load frontend assets.
	 */
	public function before()
	{
		parent::before();

		$this->_validate_csrf();
	}

	/**
	 * Set the response body to $this->view if $this->view is defined..
	 */
	public function after()
	{
		if ($this->view != NULL)
		{
			$renderer = Kostache_Layout::factory();
			$renderer->set_layout($this->layout);
			$this->response->body($renderer->render($this->view));
		}
	}

	/**
	 * Check to ensure POST requests contains CSRF.
	 * @throws HTTP_Exception
	 */
	private function _validate_csrf()
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
