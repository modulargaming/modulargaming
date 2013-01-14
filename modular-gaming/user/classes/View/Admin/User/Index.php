<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_User_Index extends Abstract_View_Admin {

	public $title = 'Users';

	public function users()
	{
		$users = array();

		foreach ($this->users as $user)
		{
			$users[] = array(
				'id'         => $user->id,
				'username'   => $user->username,
				'email'      => $user->email,
				'last_login' => Date::format($user->last_login),
				'created'    => Date::format($user->created),
				'links' => array(
					'profile' => Route::url('user.view', array('id' => $user->id)),
					'edit'    => '/admin/user/edit/'.$user->id, // TODO: Use reverse routing!
				),
			);
		}

		return $users;
	}

	public function edit_template()
	{
		$file = Kohana::find_file('templates', 'Admin/User/Edit', 'mustache');
		return file_get_contents($file);
	}

	// UGLY!
	public function modal()
	{
		return '<div class="modal hide fade" role="dialog">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3>{{header}}</h3>
		</div>
		<div class="modal-body">
			{{{body}}}
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">{{action}}</button>
		</div>
	</div>';
	}

}