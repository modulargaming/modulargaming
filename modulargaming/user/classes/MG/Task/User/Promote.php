<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Task to promote a user to admin
 *
 * @package    MG/User
 * @category   Task
 * @author     Kohana Team
 * @copyright  (c) 2009-2011 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class MG_Task_User_Promote extends Minion_Task
{
     protected $_options = array(
         'username' => NULL,
	     'type' => 'id',
	     'roles' => 2 //default to admin
    );

	/**
	 * Promote a
	 *
	 * Accepts the following options:
	 *  - username: the user you want to promote
	 *  - type: [id, name] How you'll assign the role (defaults to id)
	 *  - roles: a string(single) or multiple(separated by a comma) of {type} role(s) (defaults to admin)
	 *
	 * @return null
	 */
	protected function _execute(array $params)
	{
		$user = ORM::factory('User')
			->where('username', '=', $params['username'])
			->find();

		if(!$user->loaded()) {
			echo 'error loading user ' . $params['username'];
		}
		else
		{
			$roles = explode(',', $params['roles']);

			foreach($roles as $role) {
				if($params['type'] == 'name') {
					$role = array('name' => $role);
				}

				if(!$user->has('roles', $role))
				{
					$user->add('roles', $role);
				}
			}
			echo 'roles assigned';
		}
	}

	public function build_validation(Validation $validation)
	{
		return parent::build_validation($validation)
			->rule('username', 'not_empty') // Require this param
			->rule('username', function($value){
				return ORM::factory('User')
					->where('username', '=', $value)
					->find()
					->loaded();
			}); // Check if the username exists
	}
}
