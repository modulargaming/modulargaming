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
	
	public function compose() {
		$list = array();
		
		foreach($this->colours as $colour) {
			$has_colour = array();
			
			foreach($this->species as $specie) {
				if($specie->has('colours', $colour))
				{
					$has_colour[] = $specie->id;
				}
			}
			$list[$colour->name] = $has_colour;
		}
		
		return json_encode($list, JSON_NUMERIC_CHECK);
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
