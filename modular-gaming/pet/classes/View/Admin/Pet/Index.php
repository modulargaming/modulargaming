<?php defined('SYSPATH') OR die('No direct script access.');

class View_Admin_Pet_Index extends Abstract_View_Admin {

	public $title = 'Pet species';

	public function species()
	{
		$species = array();

		foreach ($this->species as $specie)
		{
			$species[] = array(
				'id'          => $specie->id,
				'name'        => $specie->name,
			);
		}

		return $species;
	}

}
