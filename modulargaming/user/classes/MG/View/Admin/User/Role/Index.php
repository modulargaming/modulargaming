<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Admin_User_Role_Index extends Abstract_View_Admin {

	public $title = 'Roles';

	public function roles()
	{
		$roles = array();

		foreach ($this->roles as $role)
		{
			$roles[] = array(
				'id'          => $role->id,
				'name'        => $role->name,
				'description' => $role->description
			);
		}

		return $roles;
	}

}
