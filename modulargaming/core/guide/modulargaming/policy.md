# Policy

Policies are Access Control Lists which specifiy who can access a Controller. Below is an example which will only allow users with the role 'Admin' to view the Controller



		<?php defined('SYSPATH') OR die('No direct script access.');

		class Policy_Module_Index extends Policy {
		
			public function execute(Model_ACL_User $user, array $extra = NULL)
			{
		
				if ($user->has('roles', Model_Role::ADMIN))
				{
					return TRUE;
				}
		
				return FALSE;
			}
		}


You need to add the following to your Controller so it can use the policy.

				if ( ! $this->user->can('Module_Index'))
				{
					throw HTTP_Exception::Factory('403', 'Forbidden');
				}
