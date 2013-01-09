<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Create extends View_Base
{
	public $title = 'Create a pet';

	public function races()
	{
		$races = array();

		foreach ($this->races as $race)
		{
			$races[] = array(
				'id' => $race->id,
				'name' => $race->name,
				'description' => $race->description,
			);
		}

		return $races;
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
