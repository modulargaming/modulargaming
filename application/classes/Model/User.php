<?php defined('SYSPATH') OR die('No direct access allowed.');

class Model_User extends Model_Auth_User implements Model_ACL_User {

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE
	);

	protected $_has_many = array(
		'user_tokens' => array(
			'model' => 'user_token'
		),
		'roles' => array(
			'model' => 'Role',
			'through' => 'roles_users'
		),
		'pets' => array(
			'model' => 'Pet',
			'through' => 'user_pets'
		),
	);

	protected $_belongs_to = array(
		'timezone' => array(
			'model' => 'User_Timezone',
		),
	);

	protected $_load_with = array(
		'timezone'
	);

	public function rules()
	{
		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array(array($this, 'unique'), array('username', ':value')),
			),
			'password' => array(
				array('not_empty'),
			),
			'email' => array(
				array('not_empty'),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			),
			'timezone_id' => array(
				array('not_empty'),
				array('Model_User_Timezone::timezone_exists')
			),
		);
	}

	public function filters()
	{
		return array(
			'password' => array(
				array(array(Auth::instance(), 'hash'))
			),
			'signature' => array(
				array('Security::xss_clean'),
			),
		);
	}

	/**
	 * Check if specified user exists.
	 *
	 * @param   integer $id User id
	 * @return  bool
	 */
	static public function user_exists($id)
	{
		$user = ORM::factory('User', $id);

		return $user->loaded();
	}

	public function create_user($values, $expected)
	{
		if ( ! isset($values['timezone_id']))
		{
			$values['timezone_id'] = Kohana::$config->load('date.default_timezone');
		}
		$expected[] = 'timezone_id';

		return parent::create_user($values, $expected);
	}

	/**
	 * Wrapper method to execute ACL policies. Only returns a boolean, if you
	 * need a specific error code, look at Policy::$last_code
	 *
	 * @param string $policy_name the policy to run
	 * @param array  $args        arguments to pass to the rule
	 *
	 * @return boolean
	 */
	public function can($policy_name, $args = array())
	{
		$status = FALSE;

		try
		{
			$refl = new ReflectionClass('Policy_'.$policy_name);
			$class = $refl->newInstanceArgs();
			$status = $class->execute($this, $args);

			if ($status === TRUE)
				return TRUE;
		}
		catch (ReflectionException $ex) // try and find a message based policy
		{
			// Try each of this user's roles to match a policy
			foreach ($this->roles->find_all() as $role)
			{
				$status = Kohana::message('policy', $policy_name.'.'.$role->id);
				if ($status)
				{
					return TRUE;
				}
			}
		}

		// We don't know what kind of specific error this was
		if ($status === FALSE)
		{
			$status = Policy::GENERAL_FAILURE;
		}

		Policy::$last_code = $status;

		return $status === TRUE;
	}

	/**
	 * Wrapper method for self::can() but throws an exception instead of bool
	 *
	 * @param string $policy_name the policy to run
	 * @param array  $args        arguments to pass to the rule
	 *
	 * @throws Policy_Exception
	 *
	 * @return null
	 */
	public function assert($policy_name, $args = array())
	{
		if ($this->can($policy_name, $args) === FALSE)
		{
			throw new Policy_Exception(
				'Could not authorize policy :policy',
				array(':policy' => $policy_name),
				Policy::$last_code
			);
		}
	}

} // End User Model
