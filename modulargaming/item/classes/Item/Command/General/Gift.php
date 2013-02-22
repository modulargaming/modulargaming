<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Send the item to another user.
 *
 * @package    ModularGaming/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Item_Command_General_Gift extends Item_Command {

	public $default = TRUE;

	protected function _build($name)
	{
		return null;
	}

	public function validate($param)
	{
		return null;
	}

	public function perform($item, $amount, $data=null)
	{
		return null;
	}

	public function inventory()
	{
		return array('field' => array(
				'name' => 'username',
				'type' => 'text',
				'classes' => 'input-small search',
				'button' => 'Gift'
		));
	}
}
