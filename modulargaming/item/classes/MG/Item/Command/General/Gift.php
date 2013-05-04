<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Send the item to another user.
 *
 * @package    MG/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Item_Command_General_Gift extends Item_Command {

	public $default = TRUE;

	protected function _build($name)
	{
		return NULL;
	}

	public function validate($param)
	{
		return NULL;
	}

	public function perform($item, $amount, $data=null)
	{
		return NULL;
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
