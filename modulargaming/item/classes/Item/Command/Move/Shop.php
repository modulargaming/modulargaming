<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Move an item to the player's shop
 *
 * @package    ModularGaming/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class Item_Command_Move_Shop extends Item_Command_Move {
	public $default = true;

	protected function _build($name){
		return null;
	}

	public function validate($param) {
		return null;
	}

	public function perform($item, $amount, $data=null) {
		$name = $item->item->name($amount);

		if(!$item->move('shop', $amount))
			return false;
		else
			return 'You have successfully moved ' . $name . ' to your shop.';
	}

}
