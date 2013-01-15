<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Pets_Index extends Abstract_View_Admin {

	public $title = 'Pets';

	public function pets()
	{
		$pets = array();

		foreach ($this->pets as $pet)
		{
			$pets[] = array(
				'id'          => $pet->id,
				'name'        => $pet->name,
				'race'        => $pet->race,
				'colour'      => $pet->colour,
				'created'     => Date::format($pet->created),
			);
		}

		return $pets;
	}

}
