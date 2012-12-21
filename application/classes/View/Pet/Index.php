<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Index extends View_Base
{
	public $title = 'Pets';
	
	public function pets()
	{
		$pets = array();

		foreach ($this->pets as $pet)
		{
			$pets[] = array(
				'id' => $pet->id,
				'active' => $pet->user->active,
				'src'     => URL::base().'assets/img/pets/'.$pet->race_id.'/'.$pet->colour->image,
				'name' => $pet->name,
				'colour' => $pet->colour,
				'race' => $pet->race,
			);
		}

		return $pets;
	}
	
	public function total_pets()
	{
		return count($this->pets);
	}
}
