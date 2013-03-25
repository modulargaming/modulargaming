<?php defined('SYSPATH') OR die('No direct script access.');
 
class Policy_Forum_Topic_Reply extends Policy_Forum_Topic {

	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
		$topic = $extra['topic'];

		if ($topic->locked == 0)
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
