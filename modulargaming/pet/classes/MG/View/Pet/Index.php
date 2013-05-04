<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * View for pet index.
 *
 * @package    MG/Pet
 * @category   View
 * @author     Modular Gaming Team
 * @copyright  (c) 2012-2013 Modular Gaming Team
 * @license    BSD http://modulargaming.com/license
 */
class MG_View_Pet_Index extends Abstract_View_Pet {

	public $title = 'Your pets';

	public $pets;

	public function pets()
	{
		$pets = array();
		foreach ($this->pets as $pet)
		{
			$pets[] = array(
				'id'  => $pet->id,
				'src' => $pet->img(),
				'href' => Route::url('pet', array('name' => strtolower($pet->name))),
				'name' => $pet->name,
				'specie' => $pet->specie->name,
				'colour' => $pet->colour->name,
				'username' => $pet->user->username
			);
		}
		return $pets;
	}
}
