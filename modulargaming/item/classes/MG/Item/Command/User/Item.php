<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Give the user an item
 *
 * @package    MG/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Item_Command_User_Item extends Item_Command {

	protected function _build($name)
	{
		return array(
			'title' => 'Item',
			'search' => 'item',
			'multiple' => 1,
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-small search'
					)
				)
			)
		);
	}

	public function validate($param)
	{
		$item = ORM::factory('Item')
			->where('item.name', '=', $param)
			->find();

		return $item->loaded();
	}

	public function perform($item, $param, $data=null)
	{
		$item = ORM::factory('Item')
			->where('item.name', '=', $param)
			->find();

		Item::factory($item)->to_user(Auth::instance()->get_user()->id);

		return 'You\'ve recieved a' . $item->name;
	}
}
