<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * User Model.
 *
 * @package    MG/User
 * @category   Model
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Model_User extends Model_Auth_User implements Model_ACL_User, Interface_Property {

	protected $_table_columns = array(
		'id'          => NULL,
		'email'       => NULL,
		'username'    => NULL,
		'password'    => NULL,
		'logins'      => NULL,
		'created'     => NULL,
		'last_login'  => NULL,
		'title_id'    => NULL,
		'timezone_id' => NULL,
		'cached_properties' => NULL
	);

	protected $_created_column = array(
		'column' => 'created',
		'format' => TRUE
	);

	protected $_belongs_to = array(
		'timezone' => array(
			'model' => 'User_Timezone',
		),
		'title' => array(
			'model' => 'User_Title',
		),
		'avatar' => array(
			'foreign_key' => 'avatar_id',
			'model' => 'Avatar'
		)
	);

	protected $_has_many = array(
		'user_tokens' => array(
			'model' => 'User_Token'
		),
		'roles' => array(
			'model' => 'Role',
			'through' => 'roles_users'
		),
		'avatars' => array(
			'through' => 'users_avatars'
		),
		'properties' => array(
			'model' => 'User_Property'
		)
	);

	protected $_load_with = array(
		// 'timezone', // TODO: We should load the timezone in the auth get_user().
		'title',
	);

	protected $_serialize_columns = array(
		'cached_properties'
	);

	public function rules()
	{
		return array(
			'username' => array(
				array('not_empty'),
				array('max_length', array(':value', 32)),
				array('min_length', array(':value', 4)),
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
			'about' => array(
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
	public static function user_exists($id)
	{
		$user = ORM::factory('User', $id);

		return $user->loaded();
	}

	/**
	 * @param  array $values    Values to insert
	 * @param  array $expected  Expected values, the rest will be ignored
	 * @return Model_User
	 */
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
	 * Cache the user preferences to a local field.
	 *
	 * @param boolean $save Save the user?
	 */
	public function cache_properties($save = TRUE)
	{
		$properties = ORM::factory('User_Property')
			->where('user_id', '=', $this->id)
			->find_all();

		$cache = array();

		foreach ($properties as $p)
		{
			$cache[$p->key] = $p->value;
		}

		$this->cached_properties = $cache;

		if ($save)
		{
			$this->save();
		}
	}

	/**
	 * Get a property from the key, if undefined return an empty string.
	 *
	 * @param string $key     the key to get
	 * @param mixed  $default return value if index not found
	 *
	 * @return mixed
	 */
	public function get_property($key, $default = NULL)
	{
		return Arr::get($this->cached_properties, $key, $default);
	}

	/**
	 * Set a property, runs an insert query with ON DUPLICATE KEY UPDATE flag.
	 * If the property already exists mySQL swaps it to a update query.
	 *
	 * @param string  $key
	 * @param mixed   $value
	 */
	public function set_property($key, $value)
	{
		// Insert the value to the cached property.
		$array = $this->cached_properties;
		$array[$key] = $value;
		$this->cached_properties = $array;

		$value = $this->_serialize_value($value);

		$query = DB::query(DATABASE::INSERT, "
			INSERT INTO user_properties (user_id, `key`, value)
			VALUES (:user_id, :key, :value)
			ON DUPLICATE KEY UPDATE
				value = :value
		");

		$query->parameters(array(
			':user_id' => $this->id,
			':key'     => $key,
			':value'   => $value
		));

		$query->execute();
	}

	/**
	 * Get the users avatar class.
	 *
	 * @return Avatar
	 */
	public function avatar()
	{
		return Avatar::factory($this, $this->get_property('avatar', array()));
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
			{
				return TRUE;
			}
		}
		catch (ReflectionException $ex) // try AND find a message based policy
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
	 * @return NULL
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
