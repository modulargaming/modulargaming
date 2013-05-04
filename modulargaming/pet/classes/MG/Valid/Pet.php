<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Validation for pet.
 *
 * @package    MG/Pet
 * @category   Valid
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_Valid_Pet {

	public static function specie_exists($id)
	{
		$specie = ORM::factory('Pet_Specie', $id);

		return $specie->loaded();
	}

	public static function colour_exists($id)
	{
		$colour = ORM::factory('Pet_Colour', $id);

		return $colour->loaded();
	}

	public static function colour_available($id, $model)
	{
		$colour = ORM::factory('Pet_Colour', $id);
		$specie = ORM::factory('Pet_Specie', $model->specie_id);


		return $specie->has('colours', $colour);
	}

	public static function colour_free($id)
	{
		$colour = ORM::factory('Pet_Colour', $id);

		return $colour->locked == 0;
	}

	public static function limit($user_id)
	{
		$limit = Kohana::$config->load('pet.limit');
		$pet_count = DB::select(array(DB::expr('COUNT(*)'), 'total'))
		->from('user_pets')
		->where('user_id', '=', $user_id)
		->execute()
		->get('total');

		return ($user_id == 0 OR $pet_count < $limit);
	}
}
