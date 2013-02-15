<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Delete an item from the inventory
 *
 * @package    ModularGaming/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Item_Command_General_Remove extends Item_Command {
	public $default = true;
	
	protected function _build($name){
		return null;
	}
	
	public function validate($param) {
		return null;
	}
	
	public function perform($item, $amount, $data=null) {
		return null;
	}
}