<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Increase a pet's hunger level
 *
 * @package    MG/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Item_Command_Pet_Feed extends Item_Command_Pet {

	protected function _build($name)
	{
		return array(
			'title' => 'Pet hunger',
			'fields' => array(
				array(
					'input' => array(
						'name' => $name, 'class' => 'input-mini'
					)
				)
			)
		);
	}

	public function validate($param)
	{
		return (Valid::digit($param) AND $param > 0);
	}

	public function perform($item, $param, $pet=null)
	{
		if($pet->hunger == 100)
			return FALSE;
		else
		{
			$level = $pet->hunger +  $param;

			if($level > 100)
				$pet->hunger = 100;
			else
				$pet->hunger = $level;

			$pet->save();

			return $pet->name.' has been fed '. $item->item->name;
		}
	}
}
