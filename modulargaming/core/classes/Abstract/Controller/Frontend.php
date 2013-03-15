<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Abstract base controller for frontend controllers.
 *
 * @package    Modular Gaming
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
abstract class Abstract_Controller_Frontend extends Controller {

	/**
	 * @var Auth_ORM $auth
	 */
	protected $auth;

	/**
	 * @var Model_User $user Current user
	 */
	protected $user;

	/**
	 * @var bool User is required to be logged in?
	 */
	protected $protected = FALSE;

	/**
	 * @var Abstract_View view to render
	 */
	protected $view = NULL; // View to render.

	/**
	 * @var string layout file.
	 */
	protected $layout = 'layout';

	public function before()
	{
		$this->_validate_csrf();

		$this->auth = Auth::instance();
		$this->user = $this->auth->get_user();

		if ($this->protected == TRUE AND $this->auth->logged_in() == FALSE)
		{
			throw HTTP_Exception::Factory(403, 'Login to access this page!');
		}

		$assets = Kohana::$config->load('assets.global');
		$this->_load_assets($assets);
	}

	/**
	 * Set $this->view as response body.
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

	protected function _load_assets($config)
	{
		if (isset($config['head']))
		{
			Assets::factory('head')->load($config['head']);
		}

		if (isset($config['body']))
		{
			Assets::factory('body')->load($config['body']);
		}

	}

	protected function _register_assets($location, $config)
	{
		foreach ($config as $type => $files)
		{
			if (count($files) > 0)
			{
				foreach ($files as $desc)
				{
					$position = (isset($desc['location'])) ? $desc['location'] : 'end';
					$relative = (isset($desc['location'])) ? $desc['relative'] : NULL;
					// $options = (isset($desc['options'])) ? $desc['options'] : array();
					Assets::add($type, $desc['name'], $desc['file'], $location, $position, $relative);
				}
			}
		}
	}

} // End Frontend
