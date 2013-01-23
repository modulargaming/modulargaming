<?php defined('SYSPATH') OR die('No direct script access.');

class Policy_Forum extends Policy {

	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
		if ($user->has('roles', Model_Role::ADMIN))
		{
			return TRUE;
		}

		return FALSE;
	}
}
