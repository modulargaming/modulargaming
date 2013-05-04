<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command base class
 *
 * Define commands that items perform when they're consumed
 *
 * @package    MG/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
abstract class MG_Item_Command {

	/**
	 * Spew out a command
	 *
	 * @param string            $command
	 * @param Kohana_Validation $validation
	 *
	 * @return Item_Command
	 */
	static public function factory($command, $validation = NULL)
	{
		if (strpos($command, 'Item_Command') === false)
		{
			$command = 'Item_Command_' . $command;
		}
		return new $command($validation);
	}

	/**
	 * If a command is a default one it won't be shown in the admin
	 * @var boolean
	 */
	public $default = FALSE;

	//this command would be the only one, no extra commands would be able to get assigned
	public $allow_more = TRUE;

	/**
	 * Does the command require a pet to be loaded?
	 * @var bool
	 */
	public $load_pets = FALSE;

	/**
	 * Does the item automatically gets destroyed after performing its actions?
	 * @var bool
	 */
	public $delete_after_consume = TRUE;

	protected $_validation = NULL;

	/**
	 * Build the admin interface for this command
	 *
	 * @param string $name
	 */
	abstract protected function _build($name);

	/**
	 * Validate param for a command in the admin
	 *
	 * @param mixed $param
	 */
	abstract public function validate($param);

	/**
	 * Perform the command
	 *
	 * @param Model_User_Item $item
	 * @param string|array    $param Item defined param for the command
	 * @param mixed           $data  Extra data that could be used(e.g. pet)
	 */
	abstract public function perform($item, $param, $data = NULL);

	/**
	 * Assign an extra form field in the item action list in the inventory
	 * @return NULL|array
	 */
	public function inventory()
	{
		return NULL;
	}

	public function __construct(Kohana_Validation $validation = NULL)
	{
		$this->_validation = $validation;
	}

	/**
	 * Define the admin form field for adding a command to an item.
	 *
	 * @param string $name
	 *
	 * @return number
	 */
	public function build_admin($name)
	{
		$def = $this->_build($name);

		//if loading pets is required
		$def['pets'] = ($this->pets_required()) ? 1 : 0;

		//if multiple instances of the command may be defined
		if (!isset($def['multiple']))
		{
			$def['multiple'] = 0;
		}

		//if autocomplete search is needed
		if (!isset($def['search']))
		{
			$def['search'] = 0;
		}

		return $def;
	}

	/**
	 * If this command is a default one, don't include it in the admin
	 * @return boolean
	 */
	public function is_default()
	{
		return $this->default;
	}

	/**
	 * Check if this command requires a pet to be loaded
	 * @return boolean
	 */
	public function pets_required()
	{
		return $this->load_pets;
	}

	/**
	 * check if the item should be deleted after performing all related commands
	 * @return boolean
	 */
	public function delete()
	{
		return $this->delete_after_consume;
	}
}
