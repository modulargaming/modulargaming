<?php defined('SYSPATH') OR die('No direct script access.');

class Controller_Admin_User extends Abstract_Controller_Admin {
	protected $_root_node = 'User';
	protected $_node = 'List';

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

		Assets::factory('body_admin')->js('admin.user.index', 'admin/user/index.js');

		$users = ORM::factory('User')
			->find_all();

		$this->view = new View_Admin_User_Index;
		$this->view->locate_link = Route::url('user.admin.lookup');
		$this->view->users = $users->as_array();
	}

	/**
	 * User admin auto complete
	 *
	 * @throws HTTP_Exception
	 */
	public function action_search()
	{
		if (!$this->user->can('Admin_User_Search'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to view admin user search');
		}


		$this->view = NULL;

		$name = $this->request->query('username');

		$items = ORM::factory('User')
			->where('username', 'LIKE', '%' . $name . '%')
			->find_all();

		$list = array();

		if (count($items) > 0)
		{
			foreach ($items as $item)
			{
				$list[] = $item->username;
			}
		}

		$this->response->headers('Content-Type', 'application/json');
		$this->response->body(json_encode($list));
	}

	public function action_lookup()
	{
		$users = ORM::factory('User')
			->where('username', '=', $_POST['username'])
			->find_all();

		if($users->count() == 1)
		{
			$this->redirect(Route::url('user.admin.view', array('id' => $users[0]->id), true));
		}
		else if($users->count() == 0)
		{
			throw HTTP_Exception::factory('404', 'No user found');
		}
		else
		{
			$this->view = new View_Admin_User_lookup;
			$this->view->user = $users;
		}
	}

	public function action_view()
	{
		if ( ! $this->user->can('Admin_User_Edit'))
		{
			throw HTTP_Exception::factory('403', 'Permission denied to access admin user view');
		}
		Assets::factory('body_admin')->js('bootstrap.modal.manager', 'plugins/bootstrap-modalmanager.js');
		Assets::factory('body_admin')->js('bootstrap.modal', 'plugins/bootstrap-modal.js');
		Assets::factory('body_admin')->js('jquery.dataTable', 'plugins/jquery.dataTables.js');
		Assets::factory('head_admin')->css('bootstrap.modal', 'bootstrap-modal.css');
		Assets::factory('body_admin')->js('admin.user.view', 'admin/user/view.js');

		$id = $this->request->param('id');

		$user = ORM::factory('User', $id);

		if ( ! $user->loaded())
		{
			throw HTTP_Exception::factory('404', 'No such user');
		}

		$this->view = new View_Admin_User_View;
		$this->view->user = $user;
		$this->view->link_list = Route::url('user.admin.index');
		$this->view->link_pwd = Route::url('admin.user.modal.password', array('user_id' => $id));
		$this->view->link_submit = Route::url('user.admin.save', array('id' => $id));

		//build the game info section
		$game_info = Event::fire('admin.user.view', array($user));

		for($i=0;$i<count($game_info);$i++) {
			foreach($game_info[$i]['links'] as $info) {
				//register the javascript file
				Assets::factory('body_admin')->js('admin.user.modal.'.$info['handler'], 'admin/user/modal/'.$info['handler'].'.js');

				//Add the info to the view
				$this->view->game_info[$i]['links'][] = $info;
			}
		}

		$this->view->titles = ORM::factory('User_Title')->find_all();
		$this->view->timezones = ORM::factory('User_Timezone')->find_all();
		$this->view->roles = ORM::factory('Role')->find_all();
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

		$data = $this->request->post();

		try {
			//handle the roles
			Database::instance()->query(null, 'DELETE FROM roles_users WHERE user_id="'.$user->id.'"');

			$user->add('roles', $data['roles']);

			//handle the rest
			$user->values($data, array('username', 'email', 'title_id', 'timezone_id'))
				->save();

			Hint::success($user->username. ' edited successfully!');
			$this->redirect(Route::url('user.admin.index'));
		}
		catch(ORM_Validation_Exception $e) {
			Hint::alert('Error(s) for '.$user->username.': '.implode($e->errors('modal'), ','));
			$this->redirect(Route::url('user.admin.view', array('id' => $user->id)));
		}
	}

}
