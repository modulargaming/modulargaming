<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Pet_Index extends Abstract_View_Admin {

	public $title = 'Pet';

	public function pets()
	{
		$pets = array();

		foreach ($this->pets as $pet)
		{
			$pets[] = array(
				'id'          => $pet->id,
				'name'        => $pet->name,
				'specie'        => $pet->specie,
				'colour'      => $pet->colour,
				'created'     => Date::format($pet->created),
			);
		}

		return $pets;
	}

}
