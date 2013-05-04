<?php defined('SYSPATH') OR die('No direct script access.');

class MG_View_Admin_Pet_Specie_Index extends Abstract_View_Admin {

	public $title = 'Pet species';

	public function species()
	{
		$species = array();

		foreach ($this->species as $specie)
		{
			$species[] = array(
				'id'          => $specie->id,
				'name'        => $specie->name,
				'description' => $specie->description,
			);
		}

		return $species;
	}

	public function pet_colours()
	{
		$list = array();

		foreach ($this->colours as $colour) {
			$list[$colour->id] = array('name' => $colour->name, 'locked' => $colour->locked);
		}

		return json_encode($list, JSON_NUMERIC_CHECK);
	}

	public function colours()
	{
		$list = array();

		foreach ($this->colours as $colour) {
			$list[] = array('name' => $colour->name, 'id' => $colour->id);
		}

		return $list;
	}
}
