<?php defined('SYSPATH') OR die('No direct script access.');

class Policy_Forum_Topic_Create extends Policy_Forum_Topic {

	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
		$category = $extra['category'];

		if ($category->locked == 0)
		{
			return TRUE;
		}

		if ($user->has('roles', Model_Role::ADMIN))
		{
			return TRUE;
		}

		return FALSE;
	}

}
