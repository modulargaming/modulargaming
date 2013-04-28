<?php defined('SYSPATH') OR die('No direct script access.');

class Abstract_Controller_Admin extends Abstract_Controller_Frontend {

	protected $layout = 'Admin/layout';
	protected $protected = TRUE;
	protected $_admin = TRUE;

	protected $_root_node = 'Dashboard';
	protected $_node = 'Dashboard';

	public function before()
	{
		parent::before();

		$assets = Kohana::$config->load('assets.admin');
		$this->_load_assets($assets);
	}

	public function after()
	{
		if($this->view != null)
		{
			//for the navigation
			$this->view->root_node = $this->_root_node;
			$this->view->node = $this->_node;
			$this->view->date = Date::formatted_time('now', 'l, jS F \'y');
			$this->view->time = Date::formatted_time('now', 'G:i');
		}

		parent::after();
	}

	protected function _load_assets($config)
	{
		if (isset($config['head']))
		{
			Assets::factory('head_admin')->load($config['head']);
		}

		if (isset($config['body']))
		{
			Assets::factory('body_admin')->load($config['body']);
		}

	}

}
