<?php defined('SYSPATH') OR die('No direct script access.');

class Policy_Forum_Poll_Edit extends Policy_Forum_Poll {

	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
		$poll = $extra['poll'];

		if ($user->id == $poll->topic->user->id)
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
