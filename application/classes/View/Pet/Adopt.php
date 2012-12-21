<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Adopt extends View_Base
{
	public $title = 'Adopt a pet';
	
	public function pets()
	{
		$pets = array();

		foreach ($this->pets as $user_pet)
		{
			$pet = ORM::factory('Pet', $user_pet->pet_id);
			$colour = ORM::factory('Pet_Colour', $pet->colour_id);
			$race = ORM::factory('Pet_Race', $pet->race_id);
			$pets[] = array(
				'id' => $pet->id,
				'src'     => URL::base().'assets/img/pets/'.$pet->race_id.'/'.$pet->colour->image,
				'name' => $pet->name,
				'colour' => $colour,
				'race' => $race,
			);
		}

		return $pets;
	}
	
	public function total_pets()
	{
		return count($this->pets);
	}
}
