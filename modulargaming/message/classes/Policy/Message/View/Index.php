<?php defined('SYSPATH') OR die('No direct script access.');

class Policy_Message_View_Index extends Policy {

	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
		$message = $extra['message'];

		if ($user->id == $message->receiver->id)
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
