<?php defined('SYSPATH') OR die('No direct script access.');

class Policy_Forum_Post_Edit extends Policy_Forum_Post {

	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
		$post = $extra['post'];

		if ($user->id == $post->user->id)
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
