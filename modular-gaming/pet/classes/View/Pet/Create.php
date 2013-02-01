<?php defined('SYSPATH') OR die('No direct script access.');

class View_Pet_Create extends Abstract_View
{
	public $title = 'Create a pet';

	/**
	 * A list of pet species
	 * @var array
	 */
	public $species = array();
	
	/**
	 * A list of non-locked pet colours
	 * @var array
	 */
	public $colours = array();
	
	/**
	 * Contains the default pet specie dir
	 * @var string
	 */
	public $default_specie = false;
	
	/**
	 * Contains the default colour image name
	 * @var string
	 */
	public $default_colour = false;
	
	/**
	 * Simplify specie data
	 * @return array
	 */
	public function species()
	{
		$species = array();

		foreach ($this->species as $specie)
		{
			$species[] = array(
				'id' => $specie->id,
				'name' => $specie->name,
				'description' => $specie->description,
				'dir' => $specie->dir
			);
		}

		return $species;
	}
	
	/**
	 * Javascript reference point for species and colours
	 * 
	 * @return string JSON
	 */
	public function compose() {
		$list = array();
		
		foreach($this->species as $specie) {
			$list['species'][$specie->id] = array('dir' => $specie->dir, 'colours' => array());
		}
		
		foreach($this->colours as $colour) {
			$list['colours'][$colour->id] = array(
				'name' => $colour->name,
				'img' => $colour->image
			);
			
			$species = $colour->species->find_all();
			
			foreach($species as $specie) {
				$list['species'][$specie->id]['colours'][] = $colour->id;
			}			
		}
		
		return json_encode($list, JSON_NUMERIC_CHECK);
	}
	
	/**
	 * Simplify pet colour data
	 * @return array
	 */
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
