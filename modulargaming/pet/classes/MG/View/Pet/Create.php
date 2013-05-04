<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for pet create.
 *
 * @package    MG/Pet
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Pet_Create extends Abstract_View_Pet {

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
	public $default_specie = FALSE;

	/**
	 * Contains the default colour image name
	 * @var string
	 */
	public $default_colour = FALSE;

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
	 * Javascript reference point for species AND colours
	 *
	 * @return string JSON
	 */
	public function compose()
	{
		$list = array();

		foreach ($this->species as $specie) {
			$list['species'][$specie->id] = array('dir' => $specie->dir, 'colours' => array());
		}

		foreach ($this->colours as $colour) {
			$list['colours'][$colour->id] = array(
				'name' => $colour->name,
				'img' => $colour->image
			);

			$species = $colour->species->find_all();

			foreach ($species as $specie) {
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

	protected function get_breadcrumb()
	{
		return array_merge(parent::get_breadcrumb(), array(
			array(
				'title' => 'Create a pets',
				'href'  => Route::url('pet.create')
			)
		));
	}

}
