<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Create extends Abstract_View
{
	public $title = 'Create a pet';

	public function species()
	{
		$species = array();

		foreach ($this->species as $specie)
		{
			$species[] = array(
				'id' => $specie->id,
				'name' => $specie->name,
				'description' => $specie->description,
			);
		}

		return $species;
	}
	
	public function colours()
	{
		$colours = array();

		foreach ($this->colours as $colour)
		{
			$active = '';
			if ($colour->id == 1)
			{
				$active = ' active';
			}
			$colours[] = array(
				'id' => $colour->id,
				'name' => $colour->name,
				'image' => $colour->image,
				'active' => $active,
			);
		}

		return $colours;
	}
	
}
