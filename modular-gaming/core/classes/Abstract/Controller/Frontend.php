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

	protected $auth; // Auth instance.
	protected $user; // Current logged in user.

	protected $protected = FALSE; // Require user to be logged in.
	protected $view = null; // View to render.

	protected $layout = 'layout';

	public function before()
	{
		$this->validate_csrf();

		$this->auth = Auth::instance();
		$this->user = $this->auth->get_user();

		if ($this->protected == TRUE AND $this->auth->logged_in() == FALSE)
		{
			throw HTTP_Exception::Factory(403, 'Login to access this page!');
		}
		
		$assets = Kohana::$config->load('assets.global');
		$this->_load_assets($assets);
	}
	
	protected function _load_assets($config)
	{
		if (isset($config['head']))
		{
			$this->_register_assets('head', $config['head']);
		}

		if (isset($config['body']))
		{
			$this->_register_assets('body', $config['body']);
		}

	}
	
	protected function _register_assets($location, $config)
	{
		foreach ($config as $type => $files)
		{
			if (count($files) > 0)
			{
				foreach($files as $desc)
				{
					$position = (isset($desc['location'])) ? $desc['location'] : 'end';
					$relative = (isset($desc['location'])) ? $desc['relative'] : null;
					Assets::add($type, $desc['name'], $desc['file'], $location, $position, $relative);
				}
			}
		}
	}

	public function after()
	{
		if ($this->view != null)
		{
			$renderer = Kostache_Layout::factory();
			$renderer->set_layout($this->layout);
			$this->response->body($renderer->render($this->view));
		}
	}

	private function validate_csrf()
	{
		if ($this->request->is_ajax())
		{
			$url = URL::base('http');
			$base_url = Kohana::$base_url;
			$root = str_replace($base_url, '', $url);
			
			if ($this->request->method() == HTTP_Request::POST AND $this->request->headers('Origin') != $root
			    AND strpos($this->request->headers('Referer'), $root.$base_url) === FALSE)
			{
				throw HTTP_Exception::Factory(403, 'CSRF2 check failed!');
			}
		}
		elseif ($this->request->method() == HTTP_Request::POST AND substr($this->request->directory(), 0, 5) != 'Admin')
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
