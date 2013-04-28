<?php defined('SYSPATH') OR die('No direct script access.');

class Abstract_Controller_Modal extends Abstract_Controller_Admin {

	protected $protected = TRUE;
	protected $_player = null;

	public function before()
	{
		parent::before();

		if ( ! $this->user->can('Admin_User_Modal'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin user modals');
		}

		$id = $this->request->param('user_id');
		$this->_player = ORM::factory('User', $id);
	}

	public function after()
	{
		if($this->view != null) {
			$renderer = Kostache::factory();
			$this->response->body($renderer->render($this->view));
		}
	}
}
