<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item helper
 *
 * A collection of usefull functions that relate to items.
 *
 * @package    MG/Items
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Item {

	/**
	 * Contains a Model_Item instance
	 * @var Model_Item
	 */
	protected $_item = NULL;

	/**
	 * Load up the class by assigning a Model_Item
	 *
	 * @param integer|Model_Item $item Either an id or a model instance
	 *
	 * @throws Item_Exception
	 */
	public function __construct($item)
	{
		if (Valid::digit($item))
		{
			$id = $item;
			$item = ORM::factory('Item', $id);

			if ($item->loaded())
			{
				$this->_item = $item;
			}
			else
			{
				throw new Item_Exception('Item ":id" could not be loaded', array(':id' => $id));
			}
		}
		else if ($item->loaded())
		{
			if (is_a($item, 'Model_Item'))
			{
				$this->_item = $item;
			}
			else
			{
				throw new Item_Exception('The supplied item\'s resource does not come from a model.');
			}
		}
		else
		{
			throw new Item_Exception('Item ":name" could not be loaded', array(':name' => $item->name));
		}
	}

	/**
	 * Send x copies of the registered item to a user.
	 *
	 * @param integer|Model_User $user
	 * @param integer            $amount
	 * @param string             $location
	 *
	 * @throws Item_Exception
	 */
	public function to_user($user, $origin = "app", $amount = 1, $location = 'inventory')
	{
		if (!Valid::digit($amount))
		{
			throw new Item_Exception('The supplied amount should be a number.');
		}

		if (Valid::digit($user))
		{
			$user = ORM::factory('User', $user);
		}
		else if (!is_a($user, 'Model_User'))
		{
			throw new Item_Exception('The supplied user does not come from a model.');
		}


		if (!$user->loaded())
		{
			throw new Item_Exception('The supplied user does not exist.');
		}
		else
		{
			if ($this->_type == 'item')
			{
				$user_item = ORM::factory('User_Item')
					->where('user_id', '=', $user->id)
					->where('item_id', '=', $this->_item->id)
					->where('location', '=', $location)
					->find();
			}
			else
			{
				$user_item = $this->_item;
			}

			$action = ($amount > 0) ? '+' : '-';

			if ($user_item->loaded())
			{
				//update item amount
				$user_item->amount($action, $amount);
			}
			else if ($action == '+')
			{
				$id = ($this->_type == 'item') ? $this->_item->id : $this->_item->item_id;

				//create new copy
				$user_item = ORM::factory('User_Item')
					->values(array('user_id' => $user->id, 'item_id' => $id, 'location' => $location, 'amount' => $amount))
					->save();
			}

			Item::log('item.in.' . $origin, 'Player recieved :amount :item_name @ :origin', array(
				':amount'    => $amount,
				':item_name' => $user_item->item->name($amount, FALSE),
				':origin'    => str_replace('.', ' ', $origin)
			));

		}
	}

	/**
	 * Check if the user has this item in location x.
	 *
	 * Optionally check if the user has atleast $amount.
	 *
	 * @param string  $location
	 * @param integer $amount
	 *
	 * @return Ambigous <ORM, Database_Result, Kohana_ORM, object, mixed, number, Database_Result_Cached, multitype:>|boolean
	 */
	public function user_has($location = 'inventory', $amount = FALSE, $user = NULL)
	{

		if ($user == NULL)
		{
			$user = Auth::instance()->get_user();
		}

		$user_item = ORM::factory('User_Item')
			->where('item_id', '=', $this->_item->id)
			->where('location', '=', $location)
			->where('user_id', '=', $user->id)
			->find();

		if ($user_item->loaded() && $amount == FALSE)
		{
			return $user_item;
		}
		else if ($user_item->loaded() AND $user_item->amount >= $amount)
		{
			return $user_item;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Retrieve a user's items.
	 *
	 * By default the logged in player is used to retrieve items from.
	 * Optionally limit to only transferable items
	 * Optionally look for a relation through Item->parameter_id
	 *
	 * @param string  $location           The location to look for items
	 * @param boolean $transferable_check Check if we need to load only transferable items
	 * @param integer $parameter_id       Look for a specific linked id
	 * @param User    $other_user         Provide a user whose item's we'll be looking up
	 *
	 * @return User_Item|NULL
	 */
	static public function location($location = 'inventory', $transferable_check = FALSE, $parameter_id = NULL, $other_user = NULL)
	{
		static $user = NULL;

		if ($user == NULL && $other_user == NULL)
		{
			$user = Auth::instance()->get_user();
		}
		else if ($other_user != NULL)
		{
			$user = $other_user;
		}

		if ($user != NULL)
		{
			$items = ORM::factory('User_Item')
				->where('user_id', '=', $user->id)
				->where('location', '=', $location);

			if ($transferable_check == TRUE)
			{
				$items = $items->where('transferable', '=', 1);
			}
			if ($parameter_id != NULL)
			{
				$items = $items->where('parameter_id', '=', $parameter_id);
			}

			return $items;
		}

		return NULL;
	}

	/**
	 * Create a new log
	 *
	 * @param string     $alias   An identifier to help index logs
	 * @param string     $message A general message describing the action
	 * @param array      $params  Parameters that give insight into the action that has been performed
	 * @param Model_User $user    The user that did an action
	 *
	 * @return Ambigous <ORM, Kohana_ORM>
	 */
	static public function log($alias, $message, $params = array(), $user = NULL)
	{
		if ($user == NULL)
		{
			$user = Auth::instance()->get_user();
		}

		$values = array(
			'alias'    => $alias,
			'message'  => $message,
			'user_id'  => $user->id,
			'agent'    => Request::user_agent(array('browser', 'platform')),
			'ip'       => Request::$client_ip,
			'location' => Request::current()->uri(),
			'type'     => 'item',
			'params'   => $params,
		);

		return ORM::factory('Log')
			->values($values)
			->create();
	}

	/**
	 * Send a notification to a user based on a log.
	 *
	 * @param Moel_Log   $log          Which log we'll be basing our notification on.
	 * @param Model_User $user         User instance we'll be notifying
	 * @param string     $notification A string that can be parsed through __()
	 * @param array      $param        Params to parse the notification with (combined with $log->params)
	 * @param string     $type         Type of notification (info, error, success, warning)
	 *
	 * @return User_Notification
	 */
	public static function notify($log, $user, $notification, $param = array(), $type = "info")
	{

		$notify = Kohana::$config->load('notify.' . $notification);

		$param['username'] = $log->user->username;

		$values = array(
			'log_id'  => $log->id,
			'user_id' => $user->id,
			'title'   => $notify['title'],
			'message' => __($notify['message'], $log->params + $param),
			'icon'    => $notify['icon'],
			'type'    => $type
		);

		return ORM::factory('User_Notification')
			->values($values)
			->create();
	}

	/**
	 * Build an Item instance
	 *
	 * @param Model_Item|integer $item Could be an item id or an item model instance
	 *
	 * @return Item
	 */
	static public function factory($item)
	{
		return new Item($item);
	}

	/**
	 * Parse item commands before saving
	 *
	 * @param array $input User command definition
	 *
	 * @return array Formatted commands array
	 */
	static public function parse_commands($input)
	{
		$commands = array();

		foreach ($input as $k => $c)
		{
			//if we're dealing string as parameter or an assoc array
			if (!is_array($c) OR count(array_filter(array_keys($c), 'is_string')) > 0)
			{
				$commands[] = array('name' => $k, 'param' => $c);
			}
			else
			{
				//if multiple command instances were defined (non-assoc array)
				foreach ($c as $p)
				{
					$commands[] = array('name' => $k, 'param' => $p);
				}
			}
		}

		return $commands;
	}

	/**
	 * Load all item command classes
	 * @return array
	 */
	static public function list_commands()
	{
		static $commands = NULL;

		if ($commands == NULL)
		{
			// Include paths must be searched in reverse
			$paths = array_reverse(Kohana::list_files('classes/Item/Command/'));

			// Array of class names that have been found
			$found = array();

			foreach ($paths as $files)
			{
				$replacements = array_merge(Kohana::include_paths(), array('classes' . DIRECTORY_SEPARATOR . 'Item' . DIRECTORY_SEPARATOR . 'Command' . DIRECTORY_SEPARATOR, '.php'));

				if (is_array($files))
				{
					foreach ($files as $file)
					{
						foreach ($replacements as $replace)
							$file = str_replace($replace, '', $file);

						$found[] = $file;
					}
				}
			}
			$commands = $found;
		}

		return $commands;
	}

	/**
	 * Make sure directories have a trailing slash
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	static public function filter_type_dir($value)
	{
		return (substr($value, -1) != '/') ? $value . '/' : $value;
	}

	/**
	 * Validate item command input when creating an item
	 *
	 * @param Validation $validation Validation objec
	 * @param JSON       $value      Command to validate
	 */
	static public function validate_commands($validation, $value)
	{
		$values = json_decode($value, TRUE);

		foreach ($values as $command)
		{
			$cmd = Item_Command::factory($command['name']);

			if (!$cmd->validate($command['param']))
			{
				$validation->error('commands', $command['name']);
			}
		}
	}
}
