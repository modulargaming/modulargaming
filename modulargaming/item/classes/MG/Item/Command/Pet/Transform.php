<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Item command class
 *
 * Change a pet's specie
 *
 * @package    MG/Items
 * @category   Commands
 * @author     Maxim Kerstens
 * @copyright  (c) Modular gaming
 */
class MG_Item_Command_Pet_Transform extends Item_Command_Pet {

	protected function _build($name)
	{
		return array(
			'title' => 'Pet specie',
			'search' => 'pet-specie',
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
		$specie = ORM::factory('Pet_Specie')
			->where('pet_specie.name', '=', $param)
			->find();

		return $specie->loaded();
	}

	public function perform($item, $param, $pet = null)
	{
		$specie = ORM::factory('Pet_Specie')
		->where('pet_specie.name', '=', $param)
		->find();

		$pet->specie_id = $specie->id;
		$pet->save();

		return 'Your ' . $pet->name . ' has changed in to a ' . $specie->name;
	}
}
