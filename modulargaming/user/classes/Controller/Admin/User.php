<?php defined('SYSPATH') OR die('No direct script access.');
/**
 *
 *
 * @package    MG/User
 * @category   Controller
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class Controller_Admin_User extends Abstract_Controller_Admin {

	public function before()
	{
		parent::before();
		$this->_load_assets(Kohana::$config->load('assets.admin_user'));
	}

	public function action_index()
	{
		if ( ! $this->user->can('Admin_User_Index'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin user index');
		}

		$users = ORM::factory('User')
			->find_all();

		$this->view = new View_Admin_User_Index;
		$this->view->users = $users->as_array();
	}

	public function action_edit()
	{
		if ( ! $this->user->can('Admin_User_Edit'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin user view');
		}

		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		if ($this->request->is_ajax())
		{
			$this->response->headers('Content-Type', 'application/json');
			return $this->response->body(json_encode(array(
				'username' => $user->username,
				'email'    => $user->email,
			)));
		}

		$this->view = new View_Admin_User_Edit;
		$this->view->user = $user;
	}

}
