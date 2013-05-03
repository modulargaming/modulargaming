<?php defined('SYSPATH') OR die('No direct script access.');

class MG_Controller_Admin_User extends Abstract_Controller_Admin {

	public function action_index()
	{
		$this->redirect(Route::get('user.admin.user.index')->uri());
	}

}
